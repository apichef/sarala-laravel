<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Dummy\Queries\PostCollectionQuery;
use Sarala\Http\Requests\CollectionRequest;
use Sarala\Query\CollectionQueryBuilder;

class PostCollectionRequest extends CollectionRequest
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

    public function builder(): CollectionQueryBuilder
    {
        return new PostCollectionQuery($this);
    }
}
