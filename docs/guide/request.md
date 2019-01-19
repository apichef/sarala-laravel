---
sidebarDepth: 0
---

### Using Request

This package comes with `ApiRequestAbstract` class. You **MUST** extend when you are implementing request classes. Not doing so means that you will be unable to take advantage of this package.

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
}
```

### Sanitize includes
You must implement `allowedIncludes` method on the request class to sanitize includes.

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
    
    public function allowedIncludes(): array
    {
        return [
            'author',
            'comments.author',
            'tags',
        ];
    }
    
    // ..
}
```

### Ask for [QueryBuilder](/guide/query-builder.md) from request. (Optional)

You may implement `builder` method on the request class. And it should return a child of QueryBuilderAbstract.

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
    
    public function builder(): QueryBuilderAbstract
    {
        return new PostShowQuery($this);
    }
    
    // ..
}
```

Then on the controller you can do [this](/guide/controller.md#response).
