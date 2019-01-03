<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Dummy\Comment;
use Sarala\Transformer\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['author'];

    public function data($comment): array
    {
        return [
            'id' => (int) $comment->id,
            'body' => $comment->body,
            'created_at' => $comment->created_at->toDateString(),
        ];
    }

    public function links($data): array
    {
        return [];
    }

    public function includeAuthor(Comment $comment)
    {
        return $this->item($comment->author, new UserTransformer(), 'users');
    }
}
