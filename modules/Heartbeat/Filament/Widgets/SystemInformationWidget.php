<?php

namespace Modules\Heartbeat\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Heartbeat\Dto\CpuInformation;
use Modules\Heartbeat\Dto\DiskInformation;
use Modules\Heartbeat\Dto\MemoryInformation;

class SystemInformationWidget extends BaseWidget
{

    protected static ?string $pollingInterval = '1s';
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $cpu = CpuInformation::make()->usage();
        $memory = MemoryInformation::make()->init();
        $disk = DiskInformation::make();
        return [
            Stat::make('CPU', $cpu->usage . '%')->icon('heroicon-o-cpu-chip'),
            Stat::make('Memory', $memory->used . ' MB / ' . $memory->total . ' MB')->icon('heroicon-o-squares-2x2'),
            Stat::make('Disk', $disk->humanReadable($disk->used) . ' / ' . $disk->humanReadable($disk->total))->icon('heroicon-o-circle-stack'),
        ];
    }

}
