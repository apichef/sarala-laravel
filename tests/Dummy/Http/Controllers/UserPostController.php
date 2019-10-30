<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Controllers;

use Sarala\Dummy\Post;
use Sarala\Dummy\User;
use Sarala\Http\Controllers\BaseController;
use Sarala\Dummy\Transformers\PostTransformer;
use Sarala\Dummy\Http\Requests\PostStoreRequest;

class UserPostController extends BaseController
{
    public function index(User $user)
    {
        //
    }

    public function store(User $user, PostStoreRequest $request)
    {
        $post = new Post($request->all());
        $user->posts()->save($post);

        return $this->responseItem($post, new PostTransformer(), 'posts', 201);
    }
}
