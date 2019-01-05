<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Dummy\Post;
use Sarala\Query\ItemQueryBuilder;
use Sarala\Query\QueryParamBag;

class PostShowQuery extends ItemQueryBuilder
{
    public function init(): Builder
    {
        return Post::where('id', $this->request->route('post')->id);
    }

    public function fields()
    {
        // TODO: Implement fields() method.
    }

    public function include(QueryParamBag $includes)
    {
        PostQuery::mergeCommonInclude($this->query, $includes);
    }
}
