<?php

namespace Sarala;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;

class JsonApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sarala.php' => config_path('sarala.php')
        ], 'config');
    }

    public function register(): void
    {
        $this->app->bind(Manager::class, function ($app) {
            return (new Manager())->setSerializer(new JsonApiSerializer(config('sarala.base_url')));
        });
    }
}
