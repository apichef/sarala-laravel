<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Database\Eloquent\Builder;

class QueryHelper
{
    /** @var Builder $query */
    private $query;

    /** @var QueryParamBag $includes */
    private $includes;

    public function __construct(Builder $query, QueryParamBag $includes)
    {
        $this->query = $query;
        $this->includes = $includes;
    }

    public function exact($fields): self
    {
        if (is_string($fields)) {
            if ($this->includes->has($fields)) {
                $this->query->with($fields);
            }

            return $this;
        }

        if (! is_array($fields)) {
            throw new \InvalidArgumentException(
                'The exact() method expects a string or an array. '.gettype($fields).' given'
            );
        }

        foreach (array_intersect($this->includes->keys(), $fields) as $field) {
            $this->query->with($field);
        }

        return $this;
    }

    public function alias(string $name, $value): self
    {
        if (is_callable($value)) {
            $this->query->when($this->includes->has($name), $value);
        } else {
            $this->query->when($this->includes->has($name), function (Builder $query) use ($value) {
                $query->with($value);
            });
        }

        return $this;
    }
}
