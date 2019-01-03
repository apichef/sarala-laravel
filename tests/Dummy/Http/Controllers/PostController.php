<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Controllers;

use Sarala\Dummy\Http\Requests\PostIndexRequest;
use Sarala\Dummy\Http\Requests\PostShowRequest;
use Sarala\Dummy\Post;
use Sarala\Dummy\Transformers\PostTransformer;
use Sarala\Http\Controllers\BaseController;

class PostController extends BaseController
{
    public function index(PostIndexRequest $request)
    {
        $data = $request->builder()->fetch();

        return $this->responseCollection($data, new PostTransformer(), 'posts');
    }

    public function show(Post $post, PostShowRequest $request)
    {
        $data = $request->builder()->fetch()->first();

        return $this->responseItem($data, new PostTransformer(), 'posts');
    }
}
