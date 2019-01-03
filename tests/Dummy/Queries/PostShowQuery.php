<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Dummy\Post;
use Sarala\FetchQueryBuilderAbstract;
use Sarala\Filters;
use Sarala\Includes;

class PostShowQuery extends FetchQueryBuilderAbstract
{
    protected function init(): Builder
    {
        return Post::where('id', $this->request->route('post')->id);
    }

    protected function fields()
    {
        // TODO: Implement fields() method.
    }

    protected function filter(Filters $filters)
    {
        // TODO: Implement filter() method.
    }

    protected function include(Includes $includes)
    {
        $this->query
            ->when($includes->has('tags'), function ($query) {
                $query->with('tags');
            })
            ->when($includes->has('author'), function ($query) {
                $query->with('author');
            })
            ->when($includes->has('comments'), function ($query) {
                $query->with('comments');
            });
    }

    protected function orderBy(): array
    {
        return [];
    }
}
