<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Sarala\Query\QueryBuilderAbstract;

interface ApiRequestContract
{
    public function builder(): QueryBuilderAbstract;
}
