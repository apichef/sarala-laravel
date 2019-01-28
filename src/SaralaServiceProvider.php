<?php

namespace Sarala;

use Sarala\Query\Sorts;
use Sarala\Query\Fields;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Sarala\Query\QueryParamBag;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Serializer\DataArraySerializer;

class SaralaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sarala.php' => config_path('sarala.php'),
        ], 'config');

        Request::macro('filters', function () {
            return new QueryParamBag($this, 'filter');
        });

        Request::macro('includes', function () {
            return new QueryParamBag($this, 'include');
        });

        Request::macro('fields', function () {
            return resolve(Fields::class);
        });

        Request::macro('sorts', function () {
            return new Sorts($this);
        });

        Request::macro('allowedIncludes', function () {
            return [];
        });
    }

    public function register(): void
    {
        $this->app->singleton(Sarala::class, Sarala::class);

        $this->app->bind(JsonApiSerializer::class, function ($app) {
            return new JsonApiSerializer(config('sarala.base_url'));
        });

        $this->app->bind(DataArraySerializer::class, DataArraySerializer::class);

        $this->app->singleton(Fields::class, Fields::class);

        $this->app->bind(Manager::class, function ($app) {
            return (new Manager())->setSerializer($app->make(Sarala::class)->getSerializer());
        });
    }
}
