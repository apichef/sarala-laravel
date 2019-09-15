<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Sorts
{
    /** @var Collection $fields */
    private $fields;

    public function __construct(Request $request)
    {
        $params = $request->filled('sort') ? explode(',', $request->get('sort')) : [];

        $this->fields = collect($params)->map(function ($field) {
            $direction = SortField::SORT_ASCENDING;

            if (starts_with($field, '-')) {
                $direction = SortField::SORT_DESCENDING;
                $field = Str::after($field, '-');
            }

            return new SortField($field, $direction);
        });
    }

    public function each(callable $callback)
    {
        $this->fields->each($callback);
    }
}
