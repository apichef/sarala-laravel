---
sidebarDepth: 0
---

### Links

This package comes with `Links` class to help appending links to the resource.

> Reference: [Links](https://jsonapi.org/format/#document-links)

- [Add link always](#add-link-always)
- [Conditionally add link](#conditionally-add-link)

### Add link always

You may use `push` method to add a link to Links collection without a condition. It accepts a [Link](/guide/link.md) instance as the only parameter.

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
    
    public function links($post, $user = null): Links
    {
        return Links::make()
            ->push(Link::make('delete', route('post.destroy', $post)));
    }
    
    // ..
}
```

### Conditionally add link

You may use `when` method to add a link to Links collection conditionally. It accepts a boolean as the first parameter and [Link](/guide/link.md) instance as the second parameter.

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
    
    public function links($post, $user = null): Links
    {
        return Links::make()
            ->when(
                $user->can('delete', $post),
                Link::make('delete', route('post.destroy', $post))
            );
    }
    
    // ..
}
```

You can pass the default link as the third parameter:

```php
use Sarala\Http\Requests\ApiRequestAbstract;

class PostItemRequest extends ApiRequestAbstract
{
    // ...
    
    public function links($post, $user = null): Links
    {
        return Links::make()
            ->when(
                $post->published_at,
                Link::make('unpublish', route('unpublished-post.post', $post)),
                Link::make('publish', route('unpublished-post.destroy', $post))
            );
    }
    
    // ..
}
```
