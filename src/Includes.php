<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Support\Collection;

class Includes
{
    /** @var Collection $includes */
    private $includes;

    public function __construct(array $includes)
    {
        $this->includes = collect($includes)->mapWithKeys(function ($include) {
            $sections = explode(':', $include);

            return [$sections[0] => new IncludeField($sections)];
        });
    }

    public function has($field): bool
    {
        return $this->includes->has($field);
    }

    public function keys(): array
    {
        return $this->includes->keys()->all();
    }

    public function get($field): IncludeField
    {
        return $this->includes->get($field);
    }
}
