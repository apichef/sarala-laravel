<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sarala\Query\ParamBag;
use Sarala\Query\QueryParam;
use Sarala\Query\QueryParamBag;

class QueryParamTest extends TestCase
{
    /** @var QueryParam $queryParam */
    private $queryParam;

    public function setUp()
    {
        parent::setUp();
        $request = Request::create('/url?include=comments:limit(5):sort(created_at|desc):with(author)');
        $this->queryParam = (new QueryParamBag($request, 'include'))->get('comments');
    }

    public function test_getField()
    {
        $this->assertEquals('comments', $this->queryParam->getField());
    }

    public function test_getParams()
    {
        $this->assertInstanceOf(ParamBag::class, $this->queryParam->getParams());
    }
}
