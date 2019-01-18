### Controller

This package comes with `BaseController` class, which does the JsonApiSerializer preparation and request monitoring. You **MUST** extend the `BaseController` when you are implementing API resource controllers:

```php
use Sarala\Http\Controllers\BaseController;

class PostController extends BaseController
{
    // ...
}
```

### Response

To transform and response you may use `responseItem` and `responseCollection` accordingly. Both the methods accepts data to be transformed, transformer instance and resource key respectively. 

```php
use Sarala\Dummy\Post;
use Sarala\Http\Controllers\BaseController;
use Sarala\Dummy\Transformers\PostTransformer;
use Sarala\Dummy\Http\Requests\PostItemRequest;
use Sarala\Dummy\Http\Requests\PostCollectionRequest;

class PostController extends BaseController
{
    public function index(PostCollectionRequest $request)
    {
        $data = $request->builder()->fetch();

        return $this->responseCollection($data, new PostTransformer(), 'posts');
    }

    public function show(Post $post, PostItemRequest $request)
    {
        $data = $request->builder()->fetchFirst();

        return $this->responseItem($data, new PostTransformer(), 'posts');
    }
}
```

[Ask for builder from request?](/guide/request.md#ask-for-querybuilder-from-request-optional)
