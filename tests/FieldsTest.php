<?php

declare(strict_types=1);

namespace Sarala;

use PHPUnit\Framework\TestCase;
use Sarala\Query\Fields;

class FieldsTest extends TestCase
{
    /** @var Fields $fields */
    private $fields;

    public function setUp()
    {
        parent::setUp();
        $this->fields = new Fields([
            'posts' => 'id,title',
            'comments' => 'id,body',
        ]);
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
