<?php

namespace Modules\Barcode\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Modules\Barcode\Components\Barcode;

class BarcodeServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        Blade::componentNamespace('Modules\\Barcode\\Components', 'barcode');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'barcode');
    }

}
