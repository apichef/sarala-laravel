<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Sarala\Dummy\Post;
use Sarala\Query\QueryParamBag;
use Sarala\Query\ItemQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class PostShowQuery extends ItemQueryBuilder
{
    use PostQuery;

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
        $this->mergeCommonInclude($this->query, $includes);
    }
}
