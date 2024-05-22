<?php

namespace Modules\Heartbeat\Providers;

use Illuminate\Support\ServiceProvider;

class HeartbeatServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'heartbeat');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

}
