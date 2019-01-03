<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class FetchQueryBuilderAbstract
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

    abstract protected function init(): Builder;
    abstract protected function fields();
    abstract protected function filter(Filters $filters);
    abstract protected function include(Includes $includes);
    abstract protected function orderBy(): array;

    public function fetch()
    {
        $this->fields = $this->getFields();
        $this->query = $this->init();
        $this->fields();

        if ($this->request->filled('filter')) {
            $this->filter($this->getFilters());
        }

        if ($this->request->filled('include')) {
            $this->include($this->getIncludes());
        }

        if ($this->request->filled('sort')) {
            $this->appendSortQuery();
        }

        if ($this->request->filled('page')) {
            $perPage = $this->request->input('page.size', 10);
            $page = $this->request->input('page.number', 1);

            $paginator = $this->query
                ->paginate($perPage, ['*'], 'page[number]', $page);

            return $this->appendParamsToPaginatedUrl($paginator, [
                'fields',
                'filter',
                'include',
                'sort',
            ]);
        }

        return $this->query->get();
    }

    private function getIncludes(): Includes
    {
        return new Includes(explode(',', $this->request->get('include')));
    }

    private function getFields(): Fields
    {
        return new Fields($this->request->get('fields', []));
    }

    private function getFilters(): Filters
    {
        return new Filters($this->request->get('filter'));
    }

    private function getSortFields(): Collection
    {
        return collect(explode(',', $this->request->get('sort')));
    }

    private function appendSortQuery(): void
    {
        $allowedSorts = $this->orderBy();

        $this->getSortFields()->each(function ($field) use ($allowedSorts) {
            $direction = 'asc';

            if (starts_with($field, '-')) {
                $direction = 'desc';
                $field = str_after($field, '-');
            }

            if (in_array($field, $allowedSorts)) {
                $this->query->orderBy($field, $direction);
            }
        });
    }

    private function appendParamsToPaginatedUrl(LengthAwarePaginator $paginator, array $params): LengthAwarePaginator
    {
        foreach ($params as $param) {
            if ($this->request->filled($param)) {
                $paginator->appends($param, $this->request->get($param));
            }
        }

        if (! is_null($this->request->input('page.size'))) {
            $paginator->appends('page[size]', $this->request->input('page.size'));
        }

        return $paginator;
    }
}
