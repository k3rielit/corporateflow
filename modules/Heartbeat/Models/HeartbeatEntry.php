<?php

namespace Modules\Heartbeat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Heartbeat\Database\Factories\HeartbeatEntryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Heartbeat\Dto\GitInformation;
use Modules\Heartbeat\Dto\MemoryInformation;
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
        $memory = MemoryInformation::make()->init();

        $this->memory_total = $memory->total;
        $this->memory_used = $memory->used;
        $this->memory_free = $memory->free;
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
        $git = GitInformation::make()->discover();
        $this->git_branch = $git->branch;
        $this->git_head = $git->head;
        $this->git_head_modified_at = $git->headModifiedAt->format('Y.m.d. H:i:s');
        return $this;
    }

    public static function record(): static
    {
        return static::factory()->makeOne()->cpu()->memory()->disk()->git();
    }

}
