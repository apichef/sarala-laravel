<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Query\ParamBag;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sarala\Query\QueryParamBag;

class ParamBagTest extends TestCase
{
    /** @var ParamBag $paramBag */
    private $paramBag;

    public function setUp()
    {
        parent::setUp();
        $request = Request::create('/url?include=comments:limit(5):sort(created_at|desc):with(author)');
        $this->paramBag = (new QueryParamBag($request, 'include'))->get('comments')->getParams();
    }

    public function test_has()
    {
        $this->assertTrue($this->paramBag->has('limit'));
        $this->assertTrue($this->paramBag->has('sort'));
        $this->assertFalse($this->paramBag->has('crap'));
    }

    public function test_get()
    {
        list($limit) = $this->paramBag->get('limit');
        $this->assertEquals(5, $limit);

        list($column, $direction) = $this->paramBag->get('sort');
        $this->assertEquals('created_at', $column);
        $this->assertEquals('desc', $direction);
    }
}
