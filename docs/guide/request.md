---
sidebarDepth: 0
---

### Using Request

This package comes with `ApiRequestAbstract` class. You **MUST** extend when you are implementing request classes. If not I will find you and I will kill you.

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
}
```

### Sanitize includes
You must implements `allowedIncludes` method on the request class to sanitize includes.

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

You may implements `builder` method on the request class. And it should return a child of QueryBuilderAbstract

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
