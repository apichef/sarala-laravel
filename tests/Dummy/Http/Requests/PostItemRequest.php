<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Http\Requests\ApiRequestAbstract;
use Sarala\Dummy\Queries\PostShowQuery;
use Sarala\Query\QueryBuilderAbstract;

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
            'author',
        ];
    }

    public function builder(): QueryBuilderAbstract
    {
        return new PostShowQuery($this);
    }
}
