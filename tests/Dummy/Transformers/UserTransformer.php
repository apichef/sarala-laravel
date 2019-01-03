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

    public function data($user): array
    {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    public function links($data): array
    {
        return [];
    }

    public function includePosts(User $user)
    {
        return $this->collection($user->posts, new PostTransformer(), 'posts');
    }
}
