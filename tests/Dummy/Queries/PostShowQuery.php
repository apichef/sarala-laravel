<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Dummy\Post;
use Sarala\Includes;
use Sarala\Query\ItemQueryBuilder;

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

    public function include(Includes $includes)
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
            })
            ->when($includes->has('comments.author'), function ($query) {
                $query->with('comments.author');
            });
    }
}
