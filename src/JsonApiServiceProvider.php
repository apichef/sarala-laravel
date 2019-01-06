<?php

namespace Sarala;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use Sarala\Query\Fields;
use Sarala\Query\QueryParamBag;
use Sarala\Query\Sorts;

class JsonApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sarala.php' => config_path('sarala.php')
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
        $this->app->bind(Manager::class, function ($app) {
            return (new Manager())->setSerializer(new JsonApiSerializer(config('sarala.base_url')));
        });
    }
}
