<?php

declare(strict_types=1);

namespace Sarala\Dummy\Queries;

use Illuminate\Database\Eloquent\Builder;
use Sarala\Query\QueryMapper;
use Sarala\Query\QueryParamBag;

trait PostQuery
{
    use QueryMapper;

    public function mergeCommonInclude(Builder $query, QueryParamBag $includes)
    {
        $this->mapIncludes($query, $includes, [
            'tags',
            'author' => 'author',
            'comments' => function (Builder $query) use ($includes) {
                $query->with(['comments' => function ($query) use ($includes) {
                    $params = $includes->get('comments')->getParams();
                    $query
                        ->when($params->has('with'), function ($query) use ($params) {
                            list($include) = $params->get('with');
                            $query->with($include);
                        })
                        ->when($params->has('limit'), function ($query) use ($params) {
                            list($limit) = $params->get('limit');
                            $query->limit($limit);
                        })
                        ->when($params->has('sort'), function ($query) use ($params) {
                            list($column, $direction) = $params->get('sort');
                            $query->orderBy($column, $direction);
                        });
                }]);
            },
            'comments.author' => function (Builder $query) {
                $query->with('comments.author');
            }
        ]);
    }
}
