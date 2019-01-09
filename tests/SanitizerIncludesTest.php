<?php

declare(strict_types=1);

namespace Sarala;

class SanitizerIncludesTest extends TestCase
{
    public function test_it_sanitizer_include()
    {
        $this->apiRequest('get', route('posts.index').'?include=crap')
            ->assertStatus(403)
            ->assertJson([
                'error' => [
                    'status' => '403',
                    'title' => 'Unacceptable include',
                    'detail' => 'crap is missing in allowed includes. Allowed: tags,comments.author,author',
                ],
            ]);
    }
}
