<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Sarala\Contracts\QueryContract;
use Sarala\Fields;
use Sarala\Includes;

abstract class BaseQueryBuilder implements QueryContract
{
    /** @var Request $request */
    protected $request;

    /** @var Builder $query */
    protected $query;

    /** @var Fields $fields */
    protected $fields;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getIncludes(): Includes
    {
        return new Includes(explode(',', $this->request->get('include')));
    }

    protected function getFields(): Fields
    {
        return new Fields($this->request->get('fields', []));
    }
}
