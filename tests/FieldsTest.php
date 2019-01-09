<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Query\Fields;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class FieldsTest extends TestCase
{
    /** @var Fields $fields */
    private $fields;

    public function setUp()
    {
        parent::setUp();
        $request = Request::create('/url?fields[posts]=id,title&fields[comments]=id,body');
        $this->fields = new Fields($request);
    }

    public function test_has()
    {
        $this->assertTrue($this->fields->has('posts'));
        $this->assertFalse($this->fields->has('crap'));
    }

    public function test_get()
    {
        $this->assertEquals(['posts.id', 'posts.title'], $this->fields->get('posts'));
        $this->assertEquals(['tags.*'], $this->fields->get('tags'));
    }
}
