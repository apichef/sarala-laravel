<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;
use Sarala\Dummy\Comment;

class SparseFieldsetsTest extends TestCase
{
    public function test_can_sparse_fieldsets()
    {
        $post = factory(Post::class)->create();

        $url = route('posts.show', $post).'?fields[posts]=slug,title';
        $response = $this->withJsonApiHeaders('get', $url);

        $expected = [
            'slug',
            'title',
        ];

        $this->assertEquals($expected, array_keys($response->json('data.attributes')));
    }

    public function test_can_sparse_fieldsets_of_nesterd_resources()
    {
        $comment = factory(Comment::class)->create();
        $post = $comment->post;

        $url = route('posts.show', $post).'?include=comments&fields[posts]=slug,title&fields[comments]=body';
        $response = $this->withJsonApiHeaders('get', $url);

        $expected = [
            'body',
        ];

        $this->assertEquals($expected, array_keys($response->json('included')[0]['attributes']));
    }
}
