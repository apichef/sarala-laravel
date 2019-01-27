<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Query\QueryBuilderAbstract;
use Sarala\Dummy\Queries\PostShowQuery;
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    public function authorize(): bool
    {
        return true;
    }

    public function allowedIncludes(): array
    {
        return [
            'tags',
            'comments.author',
            'tags_count',
            'comments_count',
            'author',
        ];
    }

    public function builder(): QueryBuilderAbstract
    {
        return new PostShowQuery($this);
    }
}
