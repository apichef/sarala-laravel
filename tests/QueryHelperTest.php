<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;
use Illuminate\Http\Request;
use Sarala\Query\QueryHelper;
use Sarala\Query\QueryParamBag;

class QueryHelperTest extends TestCase
{
    public function test_can_map_key_without_a_value_as_relationship()
    {
        $request = Request::create('/url?include=comments');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->exact('comments');

        $this->assertArrayHasKey('comments', $query->getEagerLoads());
    }

    public function test_can_map_include_to_relationship_respecrtively_key_to_value()
    {
        $request = Request::create('/url?include=author');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->alias('author', 'user');

        $this->assertArrayHasKey('user', $query->getEagerLoads());
    }
}
