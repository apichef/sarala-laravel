<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Includes;

trait QueryMapper
{
    public static function mapIncludes(Builder $builder, Includes $includes, array $map)
    {
        collect($map)->each(function ($value, $key) use ($builder, $includes) {
            if (is_callable($value)) {
                $builder->when($includes->has($key), $value);
            } else {
                $include = is_numeric($key) ? $value : $key;
                $builder->when($includes->has($include), function (Builder $query) use ($value) {
                    $query->with($value);
                });
            }
        });
    }
}
