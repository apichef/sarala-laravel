<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Sarala\Filters;

interface CollectionQueryContract
{
    public function filter(Filters $filters);
    public function orderBy(): array;
}
