<?php

declare(strict_types=1);

namespace Sarala;

class SanitizerIncludesTest extends TestCase
{
    public function test_it_sanitizer_include()
    {
        $this->withJsonApiHeaders('get', route('posts.index').'?include=crap')
            ->assertStatus(403)
            ->assertJson([
                'errors' => [
                    [
                        'status' => '403',
                        'title' => 'Unacceptable include',
                        'detail' => 'crap is missing in allowed includes. Allowed: tags,comments.author,tags_count,comments_count,author',
                    ],
                ],
            ]);
    }
}
