<?php

namespace Modules\Heartbeat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Heartbeat\Database\Factories\HeartbeatEntryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;

/**
 * Some features are taken from https://github.com/laravel/pulse/blob/1.x/src/Recorders/Servers.php
 */
class HeartbeatEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpu_usage',
        'memory_used',
        'memory_free',
        'memory_total',
        'disk_used',
        'disk_free',
        'disk_total',
        'git_branch',
        'git_head',
        'git_head_modified_at',
    ];

    protected static function newFactory(): Factory
    {
        return HeartbeatEntryFactory::new();
    }

    public function cpu(): static
    {
        $cpuUsage = match (PHP_OS_FAMILY) {
            'Darwin' => (int)shell_exec('top -l 1 | grep -E "^CPU" | tail -1 | awk \'{ print $3 + $5 }\''),
            'Linux' => (int)shell_exec('top -bn1 | grep -E \'^(%Cpu|CPU)\' | awk \'{ print $2 + $4 }\''),
            'Windows' => (int)trim(shell_exec('wmic cpu get loadpercentage | more +1') ?? ''),
            'BSD' => (int)shell_exec('top -b -d 2| grep \'CPU: \' | tail -1 | awk \'{print$10}\' | grep -Eo \'[0-9]+\.[0-9]+\' | awk \'{ print 100 - $1 }\''),
            default => throw new RuntimeException('The pulse:check command does not currently support ' . PHP_OS_FAMILY),
        };
        $this->cpu_usage = $cpuUsage;
        return $this;
    }

    public function memory(): static
    {
        $memoryTotal = match (PHP_OS_FAMILY) {
            'Darwin' => intval(`sysctl hw.memsize | grep -Eo '[0-9]+'` / 1024 / 1024),
            'Linux' => intval(`cat /proc/meminfo | grep MemTotal | grep -E -o '[0-9]+'` / 1024),
            'Windows' => intval(((int)trim(`wmic ComputerSystem get TotalPhysicalMemory | more +1`)) / 1024 / 1024),
            'BSD' => intval(`sysctl hw.physmem | grep -Eo '[0-9]+'` / 1024 / 1024),
            default => throw new RuntimeException('The pulse:check command does not currently support ' . PHP_OS_FAMILY),
        };

        $memoryUsed = match (PHP_OS_FAMILY) {
            'Darwin' => $memoryTotal - intval(intval(`vm_stat | grep 'Pages free' | grep -Eo '[0-9]+'`) * intval(`pagesize`) / 1024 / 1024), // MB
            'Linux' => $memoryTotal - intval(`cat /proc/meminfo | grep MemAvailable | grep -E -o '[0-9]+'` / 1024), // MB
            'Windows' => $memoryTotal - intval(((int)trim(`wmic OS get FreePhysicalMemory | more +1`)) / 1024), // MB
            'BSD' => intval(intval(`( sysctl vm.stats.vm.v_cache_count | grep -Eo '[0-9]+' ; sysctl vm.stats.vm.v_inactive_count | grep -Eo '[0-9]+' ; sysctl vm.stats.vm.v_active_count | grep -Eo '[0-9]+' ) | awk '{s+=$1} END {print s}'`) * intval(`pagesize`) / 1024 / 1024), // MB
            default => throw new RuntimeException('The pulse:check command does not currently support ' . PHP_OS_FAMILY),
        };

        $this->memory_total = $memoryTotal;
        $this->memory_used = $memoryUsed;
        $this->memory_free = $memoryTotal - $memoryUsed;
        return $this;
    }

    public function disk(): static
    {
        $path = base_path();
        $total = intval(round(disk_total_space($path) / 1024 / 1024));
        $free = intval(round(disk_free_space($path) / 1024 / 1024));

        $this->disk_total = $total;
        $this->disk_free = $free;
        $this->disk_used = $total - $free;
        return $this;
    }

    public function git(): static
    {
        $headFile = base_path('.git/HEAD');
        if (!is_file($headFile)) {
            return $this;
        }

        try {
            $headFileContent = file_get_contents('.git/HEAD');
        } catch (\Throwable $exception) {
            return $this;
        }
        preg_match('#^ref:(.+)$#', $headFileContent, $matches);
        $currentHead = trim($matches[1]);
        $currentHeadFile = base_path(".git/{$currentHead}");

        if ($currentHead && is_file($currentHeadFile)) {
            // If the file contains the path to the hash
            $this->git_branch = $currentHead;
            try {
                $this->git_head = trim(file_get_contents($currentHeadFile) ?? '');
                $this->git_head_modified_at = date('Y-m-d H:i:s', filemtime($currentHeadFile) ?? 0);
            } catch (\Throwable $exception) {
            }
        } else if ($currentHead) {
            // When the file only contains the hash, not the path to the hash
            $this->git_head = $currentHead;
        }

        return $this;
    }

    public static function record(): static
    {
        return static::factory()->makeOne()->cpu()->memory()->disk()->git();
    }

}
