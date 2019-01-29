<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class QueryBuilderAbstract
{
    /** @var Request $request */
    protected $request;

    /** @var Builder $query */
    protected $query;

    /** @var Fields $fields */
    protected $fields;

    /** @var QueryParamBag $includes */
    protected $includes;

    /** @var QueryParamBag $filters */
    protected $filters;

    /** @var QueryHelper $queryHelper */
    private $queryHelper;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->fields = $this->request->fields();
        $this->includes = $this->request->includes();
        $this->filters = $this->request->filters();
        $this->query = $this->getInitialQuery();
        $this->queryHelper = new QueryHelper($this->query, $this->includes);
    }

    abstract protected function init();

    protected function fields()
    {
        // ..
    }

    protected function filter(QueryParamBag $filters)
    {
        // ..
    }

    protected function include(QueryParamBag $includes)
    {
        // ..
    }

    protected function sort(): array
    {
        return [];
    }

    public function exact($fields): QueryHelper
    {
        return $this->queryHelper->exact($fields);
    }

    public function countExact($fields): QueryHelper
    {
        return $this->queryHelper->countExact($fields);
    }

    public function alias($fields, $value = null): QueryHelper
    {
        return $this->queryHelper->alias($fields, $value);
    }

    public function countAlias($fields, $value = null): QueryHelper
    {
        return $this->queryHelper->countAlias($fields, $value);
    }

    public function fetch()
    {
        $this->fields();

        if ($this->request->filled('filter')) {
            $this->filter($this->filters);
        }

        if ($this->request->filled('include')) {
            $this->include($this->includes);
        }

        if ($this->request->filled('sort')) {
            $this->appendSortQuery();
        }

        if ($this->request->filled('page')) {
            return $this->appendParamsToPaginatedUrl();
        }

        return $this->query->get();
    }

    private function appendSortQuery(): void
    {
        $allowedSorts = $this->sort();

        $this->request->sorts()->each(function (SortField $field) use ($allowedSorts) {
            if (in_array($field->getField(), $allowedSorts)) {
                $this->query->orderBy($field->getField(), $field->getDirection());
            }
        });
    }

    private function appendParamsToPaginatedUrl(): LengthAwarePaginator
    {
        $perPage = $this->request->input('page.size', 10);
        $page = $this->request->input('page.number', 1);
        $paginator = $this->query->paginate($perPage, ['*'], 'page[number]', $page);

        $paginator->appends($this->request->except('page'));

        foreach ($this->request->get('filter', []) as $key => $value) {
            if (is_null($value)) {
                $value = '';
            }

            $paginator->appends("filter[{$key}]", $value);
        }

        if (! is_null($this->request->input('page.size'))) {
            $paginator->appends('page[size]', $this->request->input('page.size'));
        }

        return $paginator;
    }

    public function fetchFirst()
    {
        return $this->fetch()->first();
    }

    private function getInitialQuery()
    {
        $query = $this->init();

        if ($query instanceof Builder || $query instanceof QueryBuilder || $query instanceof Relation) {
            return $query;
        }

        throw new \Exception(
            'Expected init() method to return '.Builder::class.', '.QueryBuilder::class.' or a '.Relation::class.'. '.gettype($query).' returned.'
        );
    }
}
