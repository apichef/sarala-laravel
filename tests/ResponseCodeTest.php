<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Support\Arr;
use Sarala\Dummy\Post;

class ResponseCodeTest extends TestCase
{
    public function test_it_allows_to_change_response_code()
    {
        $post = factory(Post::class)->make();
        $url = route('users.posts.store', ['user' => $post->author_id]);
        $data = Arr::except($post->toArray(), ['author_id', 'published_at']);

        $this->postJson($url, $data)
            ->assertStatus(201);
    }
}
