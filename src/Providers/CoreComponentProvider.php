<?php

namespace VCComponent\Laravel\Vicoders\Core\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Vicoders\Core\Contracts\FileValidatorInterface;
use VCComponent\Laravel\Vicoders\Core\Validators\FileValidator;

class CoreComponentProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileValidatorInterface::class, FileValidator::class);
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
         $this->publishes([
            __DIR__ . '/../../config/core.php'           => config_path('core.php'),
        ]);
    }
}
