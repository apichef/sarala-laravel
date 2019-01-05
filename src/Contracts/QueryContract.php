<?php

declare(strict_types=1);

namespace Sarala\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Query\QueryParamBag;

interface QueryContract
{
    public function fetch();
    public function init(): Builder;
    public function fields();
    public function include(QueryParamBag $includes);
}
