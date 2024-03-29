<?php

namespace Modules\Clubcard\Providers;

use Illuminate\Support\ServiceProvider;

class ClubcardServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->app->register(ClubcardRouteServiceProvider::class);
    }

}
