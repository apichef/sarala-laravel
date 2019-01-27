<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Sarala\Query\QueryParamBag;
use Illuminate\Database\Eloquent\Builder;

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
                                list($limit) = $includes->get('comments.limit');
                                $query->limit($limit);
                            })
                            ->when($includes->has('comments.sort'), function ($query) use ($includes) {
                                list($column, $direction) = $includes->get('comments.sort');
                                $query->orderBy($column, $direction);
                            });
                    }]);
                }
            ])
            ->countExact([
                'comments_count',
                'tags_count',
            ]);
    }
}
