<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QueryParamBag
{
    private $params = [];

    public function __construct(Request $request, string $field)
    {
        if ($request->filled($field)) {
            $this->prepareParams($request->get($field));
        }
    }

    public function has($field): bool
    {
        return Arr::has($this->params, $field);
    }

    public function keys(): array
    {
        return array_keys($this->params);
    }

    public function get($field, $default = null)
    {
        return Arr::get($this->params, $field, $default);
    }

    public function isEmpty($field)
    {
        return empty($this->get($field));
    }

    public function each($callback)
    {
        collect($this->params)->each($callback);
    }

    private function prepareParams($value): void
    {
        if (is_string($value)) {
            $this->prepareStringBasedParams($value);
        } elseif (is_array($value)) {
            $this->prepareArrayBasedParams($value);
        }
    }

    private function prepareStringBasedParams($value): void
    {
        collect(explode(',', $value))->each(function ($param) {
            $sections = explode(':', $param);
            $params = $this->prepareStringBasedNestedParams(array_slice($sections, 1));

            $this->params[$sections[0]] = $params;
        });
    }

    private function prepareStringBasedNestedParams(array $params): array
    {
        return collect($params)->mapWithKeys(function ($param) {
            $paramSections = explode('(', $param);

            return [$paramSections[0] => explode('|', str_replace(')', '', $paramSections[1]))];
        })->all();
    }

    private function prepareArrayBasedParams($value): void
    {
        collect($value)->each(function ($params, $field) {
            if (is_array($params) === false && trim($params) === '') {
                $params = [];
            }

            if (is_array($params)) {
                $params = $this->prepareArrayBasedNestedParams($params);
            }

            $this->params[$field] = $params;
        });
    }

    private function prepareArrayBasedNestedParams(array $params): array
    {
        return collect($params)->mapWithKeys(function ($param, $name) {
            return [$name => explode('|', $param)];
        })->all();
    }
}
