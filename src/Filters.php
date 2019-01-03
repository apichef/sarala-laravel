<?php

declare(strict_types=1);

namespace Sarala;

class Filters
{
    /** @var array $fields */
    private $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function has($field): bool
    {
        return array_key_exists($field, $this->fields);
    }
}
