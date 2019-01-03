<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Sarala\Dummy\Queries\PostShowQuery;
use Sarala\FetchQueryBuilderAbstract;
use Sarala\Http\Requests\ApiRequestAbstract;

class PostShowRequest extends ApiRequestAbstract
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
        return new PostShowQuery($this);
    }
}
