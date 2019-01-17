<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QueryParamBag
{
    /** @var Collection $params */
    private $params;

    public function __construct(Request $request, string $field)
    {
        $this->params = collect();

        if ($request->filled($field)) {
            $this->prepareParams($request->get($field));
        }
    }

    public function has($field): bool
    {
        return $this->params->has($field);
    }

    public function keys(): array
    {
        return $this->params->keys()->all();
    }

    public function get($field): ?QueryParam
    {
        return $this->params->get($field);
    }

    public function each($callback)
    {
        $this->params->each($callback);
    }

    protected function prepareParams($value): void
    {
        if (is_string($value)) {
            $this->prepareStringBasedParams($value);
        } elseif (is_array($value)) {
            $this->prepareArrayBasedParams($value);
        }
    }

    protected function prepareStringBasedParams($value): void
    {
        collect(explode(',', $value))->each(function ($param) {
            $sections = explode(':', $param);
            $params = $this->prepareStringBasedNestedParams(array_slice($sections, 1));

            return $this->params->put($sections[0], new QueryParam($sections[0], $params));
        });
    }

    private function prepareStringBasedNestedParams(array $params): array
    {
        return collect($params)->mapWithKeys(function ($param) {
            $paramSections = explode('(', $param);

            return [$paramSections[0] => explode('|', str_replace(')', '', $paramSections[1]))];
        })->all();
    }

    protected function prepareArrayBasedParams($value): void
    {
        collect($value)->each(function ($params, $field) {
            if ($params === '') {
                $params = [];
            }

            $params = $this->prepareArrayBasedNestedParams($params);

            return $this->params->put($field, new QueryParam($field, $params));
        });
    }

    private function prepareArrayBasedNestedParams(array $params): array
    {
        return collect($params)->mapWithKeys(function ($param, $name) {
            return [$name => explode('|', $param)];
        })->all();
    }
}
