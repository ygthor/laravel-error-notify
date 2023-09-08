<?php

namespace YGThor\LaravelErrorNotify;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;

class LaravelErrorNotifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $configPath = __DIR__ . '/config/laravel-error-notify.php';

            $this->publishes([
                $configPath => config_path('laravel-error-notify.php'),
            ], 'config');
        }
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ygthor-laravel-error-notify');

    }



    public function register()
    {
        $this->app->singleton(
            ExceptionHandler::class,
            \YGThor\LaravelErrorNotify\Exceptions\DevTrackHandler::class
        );
    }
}
