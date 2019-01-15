<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Query\QueryBuilderAbstract;
use Sarala\Http\Requests\ApiRequestAbstract;
use Sarala\Dummy\Queries\PostCollectionQuery;

class PostCollectionRequest extends ApiRequestAbstract
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
        return new PostCollectionQuery($this);
    }
}
