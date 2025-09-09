<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->routes(function () {
            // API route’ları yükleniyor
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web route’ları yükleniyor
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
