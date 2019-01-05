<?php

declare(strict_types=1);

namespace Sarala\Query;

class SortField
{
    const SORT_ASCENDING = 'asc';
    const SORT_DESCENDING = 'desc';

    /** @var string $field */
    private $field;

    /** @var string $direction */
    private $direction;

    public function __construct(string $field, string $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
