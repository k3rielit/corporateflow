<?php

namespace Modules\Clubcard\Providers;

use Illuminate\Support\ServiceProvider;

class ClubcardServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Configuration/clubcard.php', 'clubcard');
        $this->app->register(ClubcardRouteServiceProvider::class);
    }

}
