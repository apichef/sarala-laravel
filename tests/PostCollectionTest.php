<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Comment;
use Sarala\Dummy\Post;

class PostCollectionTest extends TestCase
{
    public function test_can_fetch_posts()
    {
        factory(Post::class, 10)->create();

        $this->apiRequest('get', route('posts.index'))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'attributes' => [
                            'slug',
                            'title',
                            'subtitle',
                            'created_at',
                            'updated_at',
                            'published_at',
                        ],
                        'links' => [
                            'self',
                            'comments',
                            'tags',
                            'author',
                        ]
                    ]
                ]
            ]);
    }

    public function test_can_fetch_a_posts_with_comments_and_author()
    {
        factory(Comment::class, 10)->create();

        $url = route('posts.index') . '?include=comments.author';

        $this->apiRequest('get', $url)
            ->assertOk();
    }
}
