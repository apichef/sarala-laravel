<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Http\Request;
use Sarala\Contracts\QueryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class QueryBuilderAbstract implements QueryContract
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
        $this->query = $this->init();
        $this->queryHelper = new QueryHelper($this->query, $this->includes);
    }

    public function fields()
    {
        // ..
    }

    public function filter(QueryParamBag $filters)
    {
        // ..
    }

    public function include(QueryParamBag $includes)
    {
        // ..
    }

    public function orderBy(): array
    {
        return [];
    }

    public function exact($fields): QueryHelper
    {
        return $this->queryHelper->exact($fields);
    }

    public function alias(string $name, $value): QueryHelper
    {
        return $this->queryHelper->alias($name, $value);
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
        $allowedSorts = $this->orderBy();

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

        if (! is_null($this->request->input('page.size'))) {
            $paginator->appends('page[size]', $this->request->input('page.size'));
        }

        return $paginator;
    }

    public function fetchFirst()
    {
        return $this->fetch()->first();
    }
}
