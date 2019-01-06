<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sarala\Query\QueryParam;
use Sarala\Query\QueryParamBag;

class QueryParamBagTest extends TestCase
{
    /** @var QueryParamBag $queryParamBag */
    private $queryParamBag;

    public function setUp()
    {
        parent::setUp();
        $request = Request::create('/url?include=comments,tags');
        $this->queryParamBag = new QueryParamBag($request, 'include');
    }

    public function test_has()
    {
        $this->assertTrue($this->queryParamBag->has('comments'));
        $this->assertTrue($this->queryParamBag->has('tags'));
        $this->assertFalse($this->queryParamBag->has('crap'));
    }

    public function test_keys()
    {
        $this->assertEquals(['comments', 'tags'], $this->queryParamBag->keys());
    }

    public function test_get()
    {
        $this->assertInstanceOf(QueryParam::class, $this->queryParamBag->get('comments'));
        $this->assertNull($this->queryParamBag->get('crap'));
    }
}
