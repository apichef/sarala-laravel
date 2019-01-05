<?php

declare(strict_types=1);

namespace Sarala\Query;

use Sarala\Contracts\ItemQueryContract;

abstract class ItemQueryBuilder extends BaseQueryBuilder implements ItemQueryContract
{
    public function fetch()
    {
        $this->fields = $this->request->fields();
        $this->query = $this->init();
        $this->fields();

        if ($this->request->filled('include')) {
            $this->include($this->request->includes());
        }

        return $this->query->get();
    }

    public function fetchFirst()
    {
        return $this->fetch()->first();
    }
}
