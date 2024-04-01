<?php

namespace Modules\Clubcard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Clubcard\Commands\ClubcardGetDeviceUuid;

class ClubcardServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->commands([
            ClubcardGetDeviceUuid::class,
        ]);
        $this->mergeConfigFrom(__DIR__ . '/../Configuration/clubcard.php', 'clubcard');
        $this->app->register(ClubcardRouteServiceProvider::class);
    }

}
