<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Support\Collection;

class QueryParamBag
{
    /** @var Collection $params */
    private $params;

    public function __construct(array $params)
    {
        $this->params = collect($params)->mapWithKeys(function ($include) {
            $sections = explode(':', $include);

            return [$sections[0] => new QueryParam($sections)];
        });
    }

    public function has($field): bool
    {
        return $this->params->has($field);
    }

    public function keys(): array
    {
        return $this->params->keys()->all();
    }

    public function get($field): QueryParam
    {
        return $this->params->get($field);
    }

    public function each($callback)
    {
        return $this->params->each($callback);
    }
}
