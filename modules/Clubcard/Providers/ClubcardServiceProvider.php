<?php

namespace Modules\Clubcard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Clubcard\Commands\ClubcardGetDeviceUuid;
use Illuminate\Support\Facades\Gate;
use Modules\Clubcard\Models\Clubcard;
use Modules\Clubcard\Policies\ClubcardPolicy;

class ClubcardServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->commands([
            ClubcardGetDeviceUuid::class,
        ]);
        $this->mergeConfigFrom(__DIR__ . '/../Configuration/clubcard.php', 'clubcard');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->app->register(ClubcardRouteServiceProvider::class);
        Gate::policy(Clubcard::class, ClubcardPolicy::class);
    }

}
