<?php

namespace App\Providers;

use App\Interfaces\AuthInterface;
use App\Interfaces\FileInterface;
use App\Models\File;
use App\Services\AuthService;
use App\Services\FileService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Foundation\Application;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
/*         if(App::enviroment(['local'])) {
            $this->app->bind(AuthInterface::class, AuthService::class);
            $this->app->bind(FileInterface::class, FileService::class);
        } */

        // Binds our interfaces to the specified service
        $this->app->bind(AuthInterface::class, function (Application $app) {
            return new AuthService($app->make(AuthService::class));
        });

        $this->app->bind(FileInterface::class, function (Application $app) {
            return new FileService($app->make(FileService::class));
        });


    }
}
