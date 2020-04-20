<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class QueryHelper
{
    private $query;

    /** @var QueryParamBag */
    private $includes;

    public function __construct($query, QueryParamBag $includes)
    {
        $this->query = $query;
        $this->includes = $includes;
    }

    public function exact($fields): self
    {
        return $this->appendExact($fields, 'appendQuery');
    }

    public function countExact($fields)
    {
        return $this->appendExact($fields, 'appendCountQuery');
    }

    private function appendExact($fields, $method)
    {
        if (is_string($fields)) {
            $this->{$method}($fields, $fields);

            return $this;
        }

        if (! is_array($fields)) {
            throw new \InvalidArgumentException(
                'Expects a string or an array. '.gettype($fields).' given'
            );
        }

        foreach (array_intersect($this->includes->keys(), $fields) as $field) {
            $this->{$method}($field, $field);
        }

        return $this;
    }

    public function alias($fields, $value = null): self
    {
        return $this->appendAlias($fields, $value, 'appendQuery');
    }

    public function countAlias($fields, $value = null)
    {
        return $this->appendAlias($fields, $value, 'appendCountQuery');
    }

    private function appendAlias($fields, $value, $method): self
    {
        if (is_array($fields) && is_null($value)) {
            foreach ($fields as $field => $value) {
                $this->{$method}($field, $value);
            }
        }

        if (is_string($fields) && ! is_null($value)) {
            $this->{$method}($fields, $value);
        }

        return $this;
    }

    private function appendCountQuery($field, $relationship)
    {
        if (is_string($relationship)) {
            $this->appendQuery($field, function ($query) use ($relationship) {
                $relationship = Str::replaceLast('Count', '', Str::camel($relationship));

                $query->withCount($relationship);
            });

            return;
        }

        throw new \InvalidArgumentException(
            'countAlias() method expects second parameter to be a string. '.gettype($relationship).' given'
        );
    }

    private function appendQuery($name, $value)
    {
        if (is_callable($value)) {
            $this->query->when($this->includes->has($name), $value);

            return;
        }

        $this->query->when($this->includes->has($name), function (Builder $query) use ($value) {
            $query->with($value);
        });
    }
}
