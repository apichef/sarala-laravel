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

class JsonApiServiceProvider extends ServiceProvider
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
            return new Fields($this);
        });

        Request::macro('sorts', function () {
            return new Sorts($this);
        });
    }

    public function register(): void
    {
        $this->app->bind(JsonApiSerializer::class, function ($app) {
            return new JsonApiSerializer(config('sarala.base_url'));
        });

        $this->app->bind(DataArraySerializer::class, function ($app) {
            return new DataArraySerializer();
        });

        $this->app->bind(Manager::class, function ($app) {
            return (new Manager())->setSerializer(Sarala::resolve()->getSerializer());
        });
    }
}
