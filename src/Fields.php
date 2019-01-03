<?php

declare(strict_types=1);

namespace Sarala;

class Fields
{
    /** @var array */
    private $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function has($resourceName)
    {
        return array_key_exists($resourceName, $this->fields);
    }

    public function get(string $resourceName): array
    {
        if ($this->has($resourceName)) {
            return collect(explode(',', $this->fields[$resourceName]))
                ->map(function ($field) use ($resourceName) {
                    return "{$resourceName}.{$field}";
                })
                ->all();
        }

        return ["{$resourceName}.*"];
    }
}
