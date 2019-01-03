<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Dummy\Post;
use Sarala\Transformer\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'author',
        'tags',
        'comments',
    ];

    public function data(Post $post): array
    {
        return [
            'id' => (int) $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'subtitle' => $post->subtitle,
            'body' => $post->body,
            'published_at' => $post->published_at->toDateString(),
        ];
    }

    public function links(Post $post): array
    {
        return [
            'author' => route('users.show', ['user' => $post->user_id]),
            'comments' => route('posts.comments.index', $post),
            'tags' => route('posts.tags.index', $post),
        ];
    }

    public function includeAuthor(Post $post)
    {
        return $this->item($post->author, new UserTransformer(), 'users');
    }

    public function includeTags(Post $post)
    {
        return $this->collection($post->tags, new TagTransformer(), 'tags');
    }

    public function includeComments(Post $post)
    {
        return $this->collection($post->comments, new CommentTransformer(), 'comments');
    }
}
