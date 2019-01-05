<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Sarala\Contracts\CollectionQueryContract;

abstract class CollectionQueryBuilder extends BaseQueryBuilder implements CollectionQueryContract
{
    public function fetch()
    {
        $this->fields = $this->request->fields();
        $this->query = $this->init();
        $this->fields();

        if ($this->request->filled('filter')) {
            $this->filter($this->request->filters());
        }

        if ($this->request->filled('include')) {
            $this->include($this->request->includes());
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
}
