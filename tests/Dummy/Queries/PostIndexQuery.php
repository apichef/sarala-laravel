<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Dummy\Post;
use Sarala\FetchQueryBuilderAbstract;
use Sarala\Filters;
use Sarala\Includes;

class PostIndexQuery extends FetchQueryBuilderAbstract
{
    protected function init(): Builder
    {
        return Post::query();
    }

    protected function fields()
    {
        // TODO: Implement fields() method.
    }

    protected function filter(Filters $filters)
    {
        $this->query
            ->when($filters->has('my'), function ($query) {
                $query->composedBy($this->request->user());
            });
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
