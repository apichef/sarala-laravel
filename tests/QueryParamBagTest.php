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
        $url = '/url?include[comments][limit]=5&include[comments][sort]=created_at|desc&include[author]&filter[my]&filter[before]=21-02-2019';
        $request = Request::create($url);

        $includeParam = new QueryParamBag($request, 'include');

        $this->assertTrue($includeParam->has('comments'));
        $this->assertFalse($includeParam->isEmpty('comments'));
        $this->assertTrue($includeParam->has('comments.limit'));
        $this->assertEquals([5], $includeParam->get('comments.limit'));
        $this->assertTrue($includeParam->has('comments.sort'));
        $this->assertEquals(['created_at', 'desc'], $includeParam->get('comments.sort'));
        $this->assertFalse($includeParam->has('comments.crap'));
        $this->assertTrue($includeParam->has('author'));
        $this->assertTrue($includeParam->isEmpty('author'));

        $filterParam = new QueryParamBag($request, 'filter');

        $this->assertTrue($filterParam->has('my'));
        $this->assertTrue($filterParam->has('before'));
        $this->assertTrue($filterParam->isEmpty('my'));
        $this->assertFalse($filterParam->isEmpty('before'));
        $this->assertEquals($filterParam->get('before'), '21-02-2019');
    }

    public function test_falsey_values_should_be_available(): void
    {
        $url = '/url?filter[amount]=0&filter[verified]=false';
        $request = Request::create($url);
        $filterParam = new QueryParamBag($request, 'filter');

        $this->assertSame('0', $filterParam->get('amount'));
        $this->assertSame('false', $filterParam->get('verified'));
    }

    public function test_empty_values_default_to_empty_array(): void
    {
        $url = '/url?filter[comments]=';
        $request = Request::create($url);
        $filterParam = new QueryParamBag($request, 'filter');

        $this->assertSame([], $filterParam->get('comments'));
    }
}
