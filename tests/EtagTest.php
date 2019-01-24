<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;

class EtagTest extends TestCase
{
    public function test_it_sets_the_etag_response_header()
    {
        $post = factory(Post::class)->create();

        $this->apiRequest('get', route('posts.show', $post))
            ->assertHeader('Etag');
    }

    public function test_it_returns_304_when_the_etags_are_the_same()
    {
        $post = factory(Post::class)->create();

        $r = $this->apiRequest('get', route('posts.show', $post));

        $this->apiRequest('get', route('posts.show', $post), [], [
            'If-None-Match' => $r->headers->get('etag'),
        ])
        ->assertStatus(304);
    }
}
