<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Query\QueryParamBag;

trait PostQuery
{
    public function mergeCommonInclude(QueryParamBag $includes)
    {
        $this->exact(['tags', 'author'])
            ->alias([
                'comments.author' => 'comments.user',
                'comments' => function (Builder $query) use ($includes) {
                    $query->with(['comments' => function ($query) use ($includes) {
                        $query
                            ->when($includes->has('comments.limit'), function ($query) use ($includes) {
                                [$limit] = $includes->get('comments.limit');
                                $query->limit($limit);
                            })
                            ->when($includes->has('comments.sort'), function ($query) use ($includes) {
                                [$column, $direction] = $includes->get('comments.sort');
                                $query->orderBy($column, $direction);
                            });
                    }]);
                },
            ])
            ->countExact([
                'comments_count',
                'tags_count',
            ]);
    }
}
