<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Sarala\Query\QueryParamBag;

interface CollectionQueryContract
{
    public function filter(QueryParamBag $filters);

    public function orderBy(): array;
}
