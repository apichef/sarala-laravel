<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Dummy\User;
use Sarala\Transformer\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
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

    public function includePosts(User $user)
    {
        return $this->collection($user->posts, new PostTransformer(), 'posts');
    }
}
