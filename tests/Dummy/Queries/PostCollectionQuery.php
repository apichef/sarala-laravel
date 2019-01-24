<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Sarala\Dummy\Post;
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostCollectionQuery extends QueryBuilderAbstract
{
    use PostQuery;

    protected function init(): Builder
    {
        return Post::query();
    }

    protected function filter(QueryParamBag $filters)
    {
        $this->query
            ->when($filters->has('my'), function ($query) {
                $query->composedBy($this->request->user());
            });
    }

    protected function include(QueryParamBag $includes)
    {
        $this->mergeCommonInclude($includes);
    }

    protected function sort(): array
    {
        return [
            'published_at'
        ];
    }
}
