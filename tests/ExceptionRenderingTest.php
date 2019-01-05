<?php

declare(strict_types=1);

namespace Sarala;

class ExceptionRenderingTest extends TestCase
{
    public function test_it_renders_exception()
    {
        $this->json('get', route('exception'))
            ->assertStatus(499)
            ->assertJson([
                'error' => [
                    'id' => 'ex001',
                    'code' => 'EX:SARALA:001',
                    'status' => '499',
                    'title' => 'Test Exception Title',
                    'detail' => 'More details about this error.',
                    'links' => [
                        'foo' => 'http://localhost/foos/1',
                        'bar' => 'http://localhost/bars/1',
                    ],
                    'href' => 'http://localhost/debug-exception/ex001',
                    'path' => 'foo.bar',
                ]
            ]);
    }
}
