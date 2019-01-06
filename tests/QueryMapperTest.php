<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Sarala\Dummy\Post;
use Sarala\Query\QueryMapper;
use Sarala\Query\QueryParamBag;

class QueryMapperTest extends TestCase
{
    use QueryMapper;

    public function test_can_map_key_without_a_value_as_relationship()
    {
        $request = Request::create('/url?include=comments');
        $includes = new QueryParamBag($request, 'include');

        $query = Post::query();
        $this->mapIncludes($query, $includes, ['comments']);

        $this->assertArrayHasKey('comments', $query->getEagerLoads());
    }

    public function test_can_map_include_to_relationship_respecrtively_key_to_value()
    {
        $request = Request::create('/url?include=author');
        $includes = new QueryParamBag($request, 'include');

        $query = Post::query();
        $this->mapIncludes($query, $includes, ['author' => 'user']);

        $this->assertArrayHasKey('user', $query->getEagerLoads());
    }
}
