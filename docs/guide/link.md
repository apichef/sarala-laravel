---
sidebarDepth: 0
---

### Links

This package comes with `Link` class to help creating link objects.

> Reference: [Links](https://jsonapi.org/format/#document-links)

### Creating a Link instance

You may use `make` method to create a link. It accepts key as a string and url respectively.

```php
Link::make('delete_comment', route('post.comments.index', $post));
```
```
{
  "delete_comment": "https://api.sarala.io/posts/{post}/relationships/comments"
}
```

### Adding meta data to the link object

You may use `meta` method to add meta data to the link object. It accepts key and value respectively.

```php
Link::make('delete_comment', route('post.tags.index', $post))
    ->meta('foo', 'bar');
```
```
{
    "delete_comment": {
        "href": "https://api.sarala.io/posts/{post}/relationships/comments",
        "meta": {
            "foo": "bar"
        }
    }
}
```

### Method helper functions

You may use `post`, `put`, `patch` and `delete` method to method to the meta data.

```php
Link::make('lags', route('post.tags.index', $post))
    ->delete();
```
```
{
    "delete_comment": {
        "href": "https://api.sarala.io/posts/{post}/relationships/comments",
        "meta": {
            "method": "delete"
        }
    }
}
```

### Set data helper functions

You may use `setData` method to set data key of meta data :P Ha ha it will make you API client's life easy.

```php
Link::make('lags', route('post.tags.index', $post))
    ->delete()
    ->setData([
        ['type' => 'comments', 'id' => $comment->id],
    ]);
```
```
{
    "delete_comment": {
        "href": "https://api.sarala.io/posts/{post}/relationships/comments",
        "meta": {
            "method": "delete",
            "data": [
                { "type": "comments", "id": "10" },
            ]
        }
    }
}
```
