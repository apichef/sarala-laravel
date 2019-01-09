<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Query\ItemQueryBuilder;
use Sarala\Http\Requests\ItemRequest;
use Sarala\Dummy\Queries\PostShowQuery;

class PostItemRequest extends ItemRequest
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

    public function builder(): ItemQueryBuilder
    {
        return new PostShowQuery($this);
    }
}
