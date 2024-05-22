<?php

namespace Modules\Heartbeat\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Support\Enums\MaxWidth;

class Heartbeat extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'heartbeat::filament.pages.heartbeat';
    protected static ?string $slug = 'heartbeat';

    public function getTitle(): string|Htmlable
    {
        return "Heartbeat";
    }

    public static function getNavigationLabel(): string
    {
        return "Heartbeat";
    }

    public function getHeading(): string
    {
        return "Heartbeat";
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    /**
     * @inheritDoc
     */
    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    public function getFooterWidgetsColumns(): int|string|array
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    protected function getHeaderWidgets(): array
    {
        return [
            //
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getFooterWidgets(): array
    {
        return [
            //
        ];
    }

}
