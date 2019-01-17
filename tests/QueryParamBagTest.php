<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sarala\Query\QueryParamBag;

class QueryParamBagTest extends TestCase
{
    public function test_string_based_query_paramas()
    {
        $url = '/url?include=comments:limit(5):sort(created_at|desc),author';
        $request = Request::create($url);
        $queryParam = new QueryParamBag($request, 'include');

        $this->assertTrue($queryParam->has('comments'));
        $this->assertFalse($queryParam->isEmpty('comments'));
        $this->assertTrue($queryParam->has('comments.limit'));
        $this->assertEquals([5], $queryParam->get('comments.limit'));
        $this->assertTrue($queryParam->has('comments.sort'));
        $this->assertEquals(['created_at', 'desc'], $queryParam->get('comments.sort'));
        $this->assertFalse($queryParam->has('comments.crap'));
        $this->assertTrue($queryParam->has('author'));
        $this->assertTrue($queryParam->isEmpty('author'));
    }

    public function test_array_based_query_paramas()
    {
        $url = '/url?include[comments][limit]=5&include[comments][sort]=created_at|desc&include[author]';
        $request = Request::create($url);
        $queryParam = new QueryParamBag($request, 'include');

        $this->assertTrue($queryParam->has('comments'));
        $this->assertFalse($queryParam->isEmpty('comments'));
        $this->assertTrue($queryParam->has('comments.limit'));
        $this->assertEquals([5], $queryParam->get('comments.limit'));
        $this->assertTrue($queryParam->has('comments.sort'));
        $this->assertEquals(['created_at', 'desc'], $queryParam->get('comments.sort'));
        $this->assertFalse($queryParam->has('comments.crap'));
        $this->assertTrue($queryParam->has('author'));
        $this->assertTrue($queryParam->isEmpty('author'));
    }
}
