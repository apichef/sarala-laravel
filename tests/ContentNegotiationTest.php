<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;

class ContentNegotiationTest extends TestCase
{
    public function test_response_has_json_api_content_type_header()
    {
        $post = factory(Post::class)->create();

        $this->apiRequest('get', route('posts.show', $post))
            ->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    public function test_it_does_not_accept_request_without_json_api_content_type_header()
    {
        $post = factory(Post::class)->create();

        $this->json('get', route('posts.show', $post))
            ->assertStatus(415)
            ->assertJson([
                'error' => [
                    'status' => '415',
                    'title' => 'Unsupported Media Type',
                ]
            ]);
    }
}
