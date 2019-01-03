<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Dummy\Comment;
use Sarala\Transformer\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'author',
    ];

    public function data(Comment $comment): array
    {
        return [
            'id' => (int) $comment->id,
            'body' => $comment->body,
            'created_at' => $comment->created_at->toIso8601String(),
        ];
    }

    public function links(Comment $comment): array
    {
        return [
            'author' => route('users.show', ['user' => $comment->user_id]),
        ];
    }

    public function includeAuthor(Comment $comment)
    {
        return $this->item($comment->author, new UserTransformer(), 'users');
    }
}
