<?php

declare(strict_types=1);

namespace Sarala\Query;

trait QueryMapper
{
    public function map()
    {
        return new QueryHelper($this->query, $this->includes);
    }
}
