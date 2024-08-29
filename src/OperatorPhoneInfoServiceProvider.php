<?php

declare(strict_types=1);

namespace ArtMksh\PhoneOperatorInfo;

use Illuminate\Support\ServiceProvider;

class OperatorPhoneInfoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PhoneOperatorInfo::class, function ($app) {
            return new PhoneOperatorInfo();
        });
    }

    public function boot()
    {
        //TODO: нужно добавить код для загрузки конфигураций, миграций и т.д.
    }
}