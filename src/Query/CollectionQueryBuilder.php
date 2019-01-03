<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Sarala\Contracts\CollectionQueryContract;
use Sarala\Filters;

abstract class CollectionQueryBuilder extends BaseQueryBuilder implements CollectionQueryContract
{
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
