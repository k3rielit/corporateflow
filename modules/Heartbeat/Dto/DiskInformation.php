<?php

namespace Modules\Heartbeat\Dto;

class DiskInformation
{

    public string $path;
    public int $total = 0;
    public int $free = 0;
    public int $used = 0;

    public function __construct()
    {
        $this->path = base_path();
        $this->total = disk_total_space($this->path);
        $this->free = disk_free_space($this->path);
        $this->used = $this->total - $this->free;
    }

    public static function make(): static
    {
        return new static();
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
