<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Dummy\Post;
use Sarala\Query\CollectionQueryBuilder;
use Sarala\Query\QueryParamBag;

class PostCollectionQuery extends CollectionQueryBuilder
{
    use PostQuery;

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
        $this->mergeCommonInclude($this->query, $includes);
    }

    public function orderBy(): array
    {
        return [];
    }
}
