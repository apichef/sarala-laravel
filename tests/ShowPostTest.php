<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;

class ShowPostTest extends TestCase
{
    public function test_can_fetch_a_post()
    {
        $post = factory(Post::class)->create();

        $this->withoutExceptionHandling();

        $this->json('get', route('posts.show', $post))->dump();
    }
}
