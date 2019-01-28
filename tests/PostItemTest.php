<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Tag;
use Sarala\Dummy\Post;
use Sarala\Dummy\Comment;

class PostItemTest extends TestCase
{
    public function test_can_fetch_a_post()
    {
        $post = factory(Post::class)->create();

        $this->withJsonApiHeaders('get', route('posts.show', $post))
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
                ],
            ])
            ->assertOk();
    }

    public function test_can_fetch_a_post_with_comments()
    {
        $post = factory(Post::class)->create();
        factory(Comment::class, 3)->create(['post_id' => $post]);

        $url = route('posts.show', $post).'?include=comments';

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
                ],
            ])
            ->assertJsonStructure([
                'included' => [
                    '*' => [
                        'id',
                        'type',
                        'attributes' => [
                            'body',
                            'created_at',
                        ],
                    ],
                ],
            ])
            ->assertOk();
    }

    public function test_can_fetch_a_post_with_comments_and_author()
    {
        $post = factory(Post::class)->create();
        factory(Comment::class)->create(['post_id' => $post]);

        $url = route('posts.show', $post).'?include=comments.author';

        $this->withJsonApiHeaders('get', $url)
            ->assertOk();
    }

    public function test_can_pass_params_to_includes()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        factory(Comment::class, 10)->create(['post_id' => $post]);
        $post->tags()->save(factory(Tag::class)->create());
        $post->tags()->save(factory(Tag::class)->create());

        $url = route('posts.show', $post).'?include=comments:limit(5):sort(created_at|desc)';

        $this->withJsonApiHeaders('get', $url)
            ->assertOk();
    }

    public function test_can_pass_params_to_includes_foo()
    {
        /** @var Post $post */
        $post = factory(Post::class)->create();
        factory(Comment::class, 10)->create(['post_id' => $post]);
        $post->tags()->save(factory(Tag::class)->create());
        $post->tags()->save(factory(Tag::class)->create());

        $url = route('posts.show', $post).'?include=author,comments,tags';

        $this->withJsonApiHeaders('get', $url)
            ->assertOk();
    }
}
