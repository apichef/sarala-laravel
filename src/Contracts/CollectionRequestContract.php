<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Sarala\Query\CollectionQueryBuilder;

interface CollectionRequestContract
{
    public function builder(): CollectionQueryBuilder;
}
