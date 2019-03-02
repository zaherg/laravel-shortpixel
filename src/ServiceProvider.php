<?php

namespace Zaherg\ShortPixel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/shortpixel.php' => base_path('config/shortpixel.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->app->bind('shortpixel', function () {
            $shortpixel = new ShortPixel();
            $shortpixel->setKey(config('shortpixel.key'));

            return $shortpixel;
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/shortpixel.php', 'shortpixel');
    }
}
