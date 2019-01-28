<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;
use Sarala\Dummy\User;

class PaginationTest extends TestCase
{
    public function test_it_appends_pagination_links()
    {
        factory(Post::class, 10)->create();

        $url = route('posts.index').'?include=comments.author';

        $this->withJsonApiHeaders('get', $url)
            ->assertOk();
    }

    public function test_can_paginate()
    {
        factory(Post::class, 10)->create();

        $url = route('posts.index').'?page[size]=4&page[number]=1';

        $this->withJsonApiHeaders('get', $url)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'attributes' => [
                            'slug',
                            'title',
                            'subtitle',
                            'body',
                            'created_at',
                            'updated_at',
                            'published_at',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
            ])
            ->assertJsonFragment([
                'meta' => [
                    'pagination' => [
                        'total' => 10,
                        'count' => 4,
                        'per_page' => 4,
                        'current_page' => 1,
                        'total_pages' => 3,
                    ],
                ],
            ])
            ->assertJsonFragment([
                'links' => [
                    'self' => 'http://localhost/posts?page%5Bsize%5D=4&page%5Bnumber%5D=1',
                    'first' => 'http://localhost/posts?page%5Bsize%5D=4&page%5Bnumber%5D=1',
                    'next' => 'http://localhost/posts?page%5Bsize%5D=4&page%5Bnumber%5D=2',
                    'last' => 'http://localhost/posts?page%5Bsize%5D=4&page%5Bnumber%5D=3',
                ],
            ]);
    }

    public function test_it_appends_all_query_parameters_to_pagination_links()
    {
        $user = factory(User::class)->create();

        factory(Post::class, 10)->create([
            'author_id' => $user->id,
        ]);

        $url = route('posts.index').'?include=comments&sort=-published_at&filter[my]&filter[does]=nothing&page[size]=4&page[number]=1';

        $this->actingAs($user)
            ->withJsonApiHeaders('get', $url)
            ->assertJsonFragment([
                'links' => [
                    'self' => 'http://localhost/posts?include=comments&sort=-published_at&filter%5Bdoes%5D=nothing&filter%5Bmy%5D=&filter%5Bdoes%5D=nothing&page%5Bsize%5D=4&page%5Bnumber%5D=1',
                    'first' => 'http://localhost/posts?include=comments&sort=-published_at&filter%5Bdoes%5D=nothing&filter%5Bmy%5D=&filter%5Bdoes%5D=nothing&page%5Bsize%5D=4&page%5Bnumber%5D=1',
                    'next' => 'http://localhost/posts?include=comments&sort=-published_at&filter%5Bdoes%5D=nothing&filter%5Bmy%5D=&filter%5Bdoes%5D=nothing&page%5Bsize%5D=4&page%5Bnumber%5D=2',
                    'last' => 'http://localhost/posts?include=comments&sort=-published_at&filter%5Bdoes%5D=nothing&filter%5Bmy%5D=&filter%5Bdoes%5D=nothing&page%5Bsize%5D=4&page%5Bnumber%5D=3',
                ],
            ]);
    }
}
