<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Dummy\User;
use Sarala\Transformer\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'posts',
    ];

    public function data(User $user): array
    {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toIso8601String(),
        ];
    }

    public function links(User $user): array
    {
        return [
            'posts' => route('users.posts.index', $user)
        ];
    }

    public function includePosts(User $user)
    {
        return $this->collection($user->posts, new PostTransformer(), 'posts');
    }
}
