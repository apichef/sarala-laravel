<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Sarala\Query\QueryParamBag;
use Illuminate\Database\Eloquent\Builder;

interface QueryContract
{
    public function fetch();

    public function init(): Builder;

    public function fields();

    public function include(QueryParamBag $includes);
}
