<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Dummy\Queries\PostIndexQuery;
use Sarala\FetchQueryBuilderAbstract;
use Sarala\Http\Requests\ApiRequestAbstract;

class PostIndexRequest extends ApiRequestAbstract
{
    public function authorize(): bool
    {
        return true;
    }

    public function allowedIncludes(): array
    {
        return [
            'tags',
            'comments',
            'author',
        ];
    }

    public function builder(): FetchQueryBuilderAbstract
    {
        return new PostIndexQuery($this);
    }
}
