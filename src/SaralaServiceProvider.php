<?php

namespace Sarala;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\JsonApiSerializer;
use Sarala\Query\Fields;
use Sarala\Query\QueryParamBag;
use Sarala\Query\Sorts;

class SaralaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sarala.php' => config_path('sarala.php'),
        ], 'config');

        Request::macro('filters', fn () => new QueryParamBag($this, 'filter'));
        Request::macro('includes', fn () => new QueryParamBag($this, 'include'));
        Request::macro('fields', fn () => resolve(Fields::class));
        Request::macro('sorts', fn () => new Sorts($this));
        Request::macro('allowedIncludes', fn () => []);
    }

    public function register(): void
    {
        $this->app->singleton(Sarala::class, Sarala::class);
        $this->app->bind(JsonApiSerializer::class, fn () => new JsonApiSerializer(config('sarala.base_url')));
        $this->app->bind(DataArraySerializer::class, DataArraySerializer::class);
        $this->app->singleton(Fields::class, Fields::class);
        $this->app->bind(Manager::class, fn ($app) => (new Manager())->setSerializer($app->make(Sarala::class)->getSerializer()));
    }
}
