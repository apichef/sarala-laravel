<?php

declare(strict_types=1);

namespace Sarala;

use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function test_can_create_a_link_with_name_and_url()
    {
        $link = Link::make('unicorn', 'http://localhost/unicorn');

        $this->assertEquals('unicorn', $link->name());
        $this->assertEquals('http://localhost/unicorn', $link->data());
    }

    public function test_can_create_a_post_link()
    {
        $link = Link::make('unicorn', 'http://localhost/unicorn')->post();

        $this->assertEquals([
            'href' => 'http://localhost/unicorn',
            'meta' => [
                'method' => 'post',
            ],
        ], $link->data());
        $this->assertEquals('unicorn', $link->name());
    }

    public function test_can_create_a_put_link()
    {
        $link = Link::make('unicorn', 'http://localhost/unicorn')->put();

        $this->assertEquals([
            'href' => 'http://localhost/unicorn',
            'meta' => [
                'method' => 'put',
            ],
        ], $link->data());
        $this->assertEquals('unicorn', $link->name());
    }

    public function test_can_create_a_delete_link()
    {
        $link = Link::make('unicorn', 'http://localhost/unicorn')->delete();

        $this->assertEquals([
            'href' => 'http://localhost/unicorn',
            'meta' => [
                'method' => 'delete',
            ],
        ], $link->data());
        $this->assertEquals('unicorn', $link->name());
    }

    public function test_can_set_link_data()
    {
        $link = Link::make('unicorn', 'http://localhost/unicorn')
            ->post()
            ->setData(['id' => 'p0st7']);

        $this->assertEquals([
            'href' => 'http://localhost/unicorn',
            'meta' => [
                'method' => 'post',
                'data' => [
                    'data' => ['id' => 'p0st7'],
                ],
            ],
        ], $link->data());
        $this->assertEquals('unicorn', $link->name());
    }
}
