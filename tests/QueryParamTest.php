<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Query\ParamBag;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sarala\Query\QueryParamBag;

class QueryParamTest extends TestCase
{
    public function test_string_based_query_paramas()
    {
        $request = Request::create('/url?include=comments:limit(5):sort(created_at|desc),author');
        $queryParam = (new QueryParamBag($request, 'include'))->get('comments');

        $this->assertEquals('comments', $queryParam->getField());
        $this->assertInstanceOf(ParamBag::class, $queryParam->getParams());

        $this->assertTrue($queryParam->getParams()->has('limit'));
        $this->assertEquals([5], $queryParam->getParams()->get('limit'));
        $this->assertTrue($queryParam->getParams()->has('sort'));
        $this->assertEquals(['created_at', 'desc'], $queryParam->getParams()->get('sort'));
        $this->assertFalse($queryParam->getParams()->has('crap'));
    }

    public function test_array_based_query_paramas()
    {
        $request = Request::create('/url?include[comments][limit]=5&include[comments][sort]=created_at|desc&include[author]');
        $queryParam = (new QueryParamBag($request, 'include'))->get('comments');

        $this->assertEquals('comments', $queryParam->getField());
        $this->assertInstanceOf(ParamBag::class, $queryParam->getParams());

        $this->assertTrue($queryParam->getParams()->has('limit'));
        $this->assertEquals([5], $queryParam->getParams()->get('limit'));
        $this->assertTrue($queryParam->getParams()->has('sort'));
        $this->assertEquals(['created_at', 'desc'], $queryParam->getParams()->get('sort'));
        $this->assertFalse($queryParam->getParams()->has('crap'));
    }
}
