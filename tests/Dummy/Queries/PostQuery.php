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
            ->alias('comments.author', 'comments.user')
            ->alias('comments', function (Builder $query) use ($includes) {
                $query->with(['comments' => function ($query) use ($includes) {
                    $params = $includes->get('comments')->getParams();
                    $query
                        ->when($params->has('limit'), function ($query) use ($params) {
                            list($limit) = $params->get('limit');
                            $query->limit($limit);
                        })
                        ->when($params->has('sort'), function ($query) use ($params) {
                            list($column, $direction) = $params->get('sort');
                            $query->orderBy($column, $direction);
                        });
                }]);
            });
    }
}
