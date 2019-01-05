<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Dummy\Post;
use Sarala\Query\CollectionQueryBuilder;
use Sarala\Query\QueryParamBag;

class PostCollectionQuery extends CollectionQueryBuilder
{
    public function init(): Builder
    {
        return Post::query();
    }

    public function fields()
    {
        // TODO: Implement fields() method.
    }

    public function filter(QueryParamBag $filters)
    {
        $this->query
            ->when($filters->has('my'), function ($query) {
                $query->composedBy($this->request->user());
            });
    }

    public function include(QueryParamBag $includes)
    {
        PostQuery::mergeCommonInclude($this->query, $includes);
    }

    public function orderBy(): array
    {
        return [];
    }
}
