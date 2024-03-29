<?php

namespace Modules\Clubcard\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class ClubcardRouteServiceProvider extends RouteServiceProvider
{

    public function boot(): void
    {
        $this->routes(function () {
            Route::prefix('clubcard')->middleware(['web'])->group(__DIR__ . '/../Routes/web.php');
        });
    }

}
