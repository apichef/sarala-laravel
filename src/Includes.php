<?php

declare(strict_types=1);

namespace Sarala;

class Includes
{
    /** @var array $fields */
    private $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function has($field): bool
    {
        return in_array($field, $this->fields);
    }
}
