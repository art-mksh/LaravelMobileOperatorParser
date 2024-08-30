<?php

declare(strict_types=1);

namespace ArtMksh\RossvyazApi;

use Illuminate\Support\ServiceProvider;

class PhoneOperatorInfoApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PhoneOperatorInfoService::class, function ($app) {
            return new PhoneOperatorInfoService();
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }
}