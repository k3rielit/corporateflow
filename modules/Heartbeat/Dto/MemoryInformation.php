<?php

namespace Modules\Heartbeat\Dto;

use RuntimeException;

class MemoryInformation
{

    public int $total = 0;
    public int $used = 0;
    public int $free = 0;
    public int $phpUsage = 0;
    public int $phpPeakUsage = 0;

    public function __construct()
    {
        $this->phpUsage = memory_get_usage();
        $this->phpPeakUsage = memory_get_peak_usage();
    }

    public static function make(): static
    {
        return new static();
    }

    public function init(): static
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

        $this->total = $memoryTotal;
        $this->used = $memoryUsed;
        $this->free = $this->total - $this->used;

        return $this;
    }

    public function humanReadable(int $bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}
