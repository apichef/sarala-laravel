<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Dummy\Post;
use Sarala\Link;
use Sarala\Links;
use Sarala\Transformer\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'author',
        'tags',
        'comments',
        'tags_count',
        'comments_count',
    ];

    public function data(Post $post): array
    {
        return [
            'id' => (int) $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'subtitle' => $post->subtitle,
            'body' => $post->body,
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->created_at->toIso8601String(),
            'published_at' => optional($post->published_at)->toIso8601String(),
        ];
    }

    public function links($post, $user = null): Links
    {
        return Links::make()
            ->when(
                $post->published_at,
                Link::make('unpublish', url("/published-posts/{$post->id}"))->delete(),
                Link::make('publish', url('/published-posts'))->post()->setData([
                    'id' => $post->id,
                ])
            );
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

    public function includeTagsCount(Post $post)
    {
        return $this->primitive($post->tags_count);
    }

    public function includeCommentsCount(Post $post)
    {
        return $this->primitive($post->comments_count);
    }
}
