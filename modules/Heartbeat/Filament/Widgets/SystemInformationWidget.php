<?php

namespace Modules\Heartbeat\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Heartbeat\Dto\CpuInformation;
use Modules\Heartbeat\Dto\DiskInformation;
use Modules\Heartbeat\Dto\GitInformation;
use Modules\Heartbeat\Dto\MemoryInformation;

class SystemInformationWidget extends BaseWidget
{

    protected static ?string $pollingInterval = '1s';
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $cpu = CpuInformation::make()->usage();
        $memory = MemoryInformation::make()->init();
        $disk = DiskInformation::make();
        $git = GitInformation::make()->discover();
        $timezone = config('app.timezone', 'Europe/Budapest');
        return [
            Stat::make('CPU', $cpu->usage . '%')
                ->icon('heroicon-o-cpu-chip'),
            Stat::make('Memory', number_format($memory->used, thousands_separator: ' ') . ' MB')
                ->description('From ' . number_format($memory->total, thousands_separator: ' ') . ' MB')
                ->icon('heroicon-o-squares-2x2'),
            Stat::make('Disk', $disk->humanReadable($disk->used))
                ->description('From ' . $disk->humanReadable($disk->total))
                ->icon('heroicon-o-circle-stack'),
            Stat::make('Git', $git->branch)
                ->description("Deployed " . $git->headModifiedAt->timezone($timezone)->format('Y.m.d H:i:s'))
                ->icon('heroicon-o-cloud'),
        ];
    }

}
