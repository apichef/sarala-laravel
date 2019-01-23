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

    public function test_it_throws_415_error_when_content_type_header_is_not_supported()
    {
        $post = factory(Post::class)->create();

        $this->get(route('posts.show', $post), [
            'Content-Type' => 'application/x-xml',
            'Accept' => 'application/json',
        ])
        ->assertStatus(415)
        ->assertJson([
            'error' => [
                'status' => '415',
                'title' => 'Unsupported Media Type',
            ],
        ]);
    }

    public function test_it_throws_406_error_when_caccept_header_is_not_supported()
    {
        $post = factory(Post::class)->create();

        $this->get(route('posts.show', $post), [
            'Content-Type' => 'application/json',
            'Accept' => 'application/x-xml',
        ])
        ->assertStatus(406)
        ->assertJson([
            'error' => [
                'status' => '406',
                'title' => 'Not Acceptable',
            ],
        ]);
    }
}
