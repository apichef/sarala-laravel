<?php

namespace Sarala;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use Sarala\Query\Fields;
use Sarala\Query\QueryParamBag;
use Sarala\Query\SortField;

class JsonApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sarala.php' => config_path('sarala.php')
        ], 'config');

        Request::macro('filters', function () {
            $params = $this->filled('filter') ? explode(',', $this->get('filter')) : [];

            return new QueryParamBag($params);
        });

        Request::macro('includes', function () {
            $params = $this->filled('include') ? explode(',', $this->get('include')) : [];

            return new QueryParamBag($params);
        });

        Request::macro('fields', function () {
            return new Fields($this->get('fields', []));
        });

        Request::macro('sorts', function () {
            $params = $this->filled('sort') ? explode(',', $this->get('sort')) : [];

            return collect($params)->map(function ($field) {
                $direction = SortField::SORT_ASCENDING;

                if (starts_with($field, '-')) {
                    $direction = SortField::SORT_DESCENDING;
                    $field = str_after($field, '-');
                }

                return new SortField($field, $direction);
            });
        });
    }

    public function register(): void
    {
        $this->app->bind(Manager::class, function ($app) {
            return (new Manager())->setSerializer(new JsonApiSerializer(config('sarala.base_url')));
        });
    }
}
