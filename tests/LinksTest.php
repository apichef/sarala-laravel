<?php

declare(strict_types=1);

namespace Sarala;

use PHPUnit\Framework\TestCase;

class LinksTest extends TestCase
{
    public function test_returns_empty_array_when_there_are_no_links()
    {
        $links = Links::make();

        $this->assertEquals([], $links->all());
    }

    public function test_can_push_a_link()
    {
        $link = Link::make('unicorn', 'http://localhost/unicorn');
        $links = Links::make()->push($link);

        $this->assertEquals([
            'unicorn' => 'http://localhost/unicorn',
        ], $links->all());
    }

    public function test_can_push_only_link_instance_or_a_closure()
    {
        $this->expectException(\InvalidArgumentException::class);

        Links::make()->push('not a link');
    }

    public function test_can_push_a_closure_returns_a_link()
    {
        $closure = function () {
            return Link::make('unicorn', 'http://localhost/unicorn');
        };
        $links = Links::make()->push($closure);

        $this->assertEquals([
            'unicorn' => 'http://localhost/unicorn',
        ], $links->all());
    }

    public function test_pushed_closure_must_returns_a_link()
    {
        $closure = function () {
            return 'not a link';
        };

        $this->expectException(\InvalidArgumentException::class);

        Links::make()->push($closure);
    }

    public function test_can_push_multiple_links()
    {
        $unicorn = Link::make('unicorn', 'http://localhost/unicorn');
        $hero = Link::make('hero', 'http://localhost/hero');

        $links = Links::make()
            ->push($unicorn)
            ->push($hero);

        $this->assertEquals([
            'unicorn' => 'http://localhost/unicorn',
            'hero' => 'http://localhost/hero',
        ], $links->all());
    }

    public function test_can_push_conditional_links()
    {
        $unicorn = Link::make('unicorn', 'http://localhost/unicorn');
        $hero = Link::make('hero', 'http://localhost/hero');

        $links = Links::make()
            ->when(true, $unicorn)
            ->when(false, $hero);

        $this->assertEquals([
            'unicorn' => 'http://localhost/unicorn',
        ], $links->all());
    }

    public function test_can_push_conditional_links_with_default()
    {
        $unicorn = Link::make('unicorn', 'http://localhost/unicorn');
        $hero = Link::make('hero', 'http://localhost/hero');

        $links = Links::make()
            ->when(false, $unicorn, $hero);

        $this->assertEquals([
            'hero' => 'http://localhost/hero',
        ], $links->all());
    }
}
