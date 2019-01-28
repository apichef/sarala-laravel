<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;

class ContentNegotiationTest extends TestCase
{
    public function test_response_has_json_api_content_type_header()
    {
        $post = factory(Post::class)->create();

        $this->withJsonApiHeaders('get', route('posts.show', $post))
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

    public function test_response_according_to_accept_header_json_api()
    {
        $post = factory(Post::class)->create();

        $url = route('posts.show', $post).'?include=author';

        $this->withJsonApiHeaders('get', $url)
            ->assertJson([
                'data' => [
                    'id' => (int) $post->id,
                    'type' => 'posts',
                    'attributes' => [
                        'slug' => $post->slug,
                        'title' => $post->title,
                        'subtitle' => $post->subtitle,
                        'body' => $post->body,
                        'created_at' => $post->created_at->toIso8601String(),
                        'updated_at' => $post->created_at->toIso8601String(),
                        'published_at' => optional($post->published_at)->toIso8601String(),
                    ],
                    'links' => [
                        'self' => route('posts.show', $post),
                    ],
                    'relationships' => [
                        'author' => [
                            'links' => [
                                'self' => 'http://localhost/posts/1/relationships/author',
                                'related' => 'http://localhost/posts/1/author',
                            ],
                            'data' => [
                                'type' => 'users',
                                'id' => '1',
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'type' => 'users',
                        'id' => '1',
                        'attributes' => [
                            'name' => $post->author->name,
                            'email' => $post->author->email,
                            'created_at' => $post->author->created_at->toIso8601String(),
                        ],
                        'links' => [
                            'self' => 'http://localhost/users/1',
                        ],
                    ],
                ],
            ])
            ->assertOk();
    }

    public function test_response_according_to_accept_header_json()
    {
        $post = factory(Post::class)->create();

        $url = route('posts.show', $post).'?include=author';

        $this->json('get', $url)
            ->assertJson([
                'data' => [
                    'id' => (int) $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'subtitle' => $post->subtitle,
                    'body' => $post->body,
                    'created_at' => $post->created_at->toIso8601String(),
                    'updated_at' => $post->created_at->toIso8601String(),
                    'published_at' => optional($post->published_at)->toIso8601String(),
                    'author' => [
                        'data' => [
                            'id' => '1',
                            'name' => $post->author->name,
                            'email' => $post->author->email,
                            'created_at' => $post->author->created_at->toIso8601String(),
                        ],
                    ],
                ],
            ])
            ->assertOk();
    }
}
