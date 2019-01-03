<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Sarala\Query\ItemQueryBuilder;

interface ItemRequestContract
{
    public function builder(): ItemQueryBuilder;
}
