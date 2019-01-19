---
sidebarDepth: 0
---

### Transformer

This package is a [Fractal](http://fractal.thephpleague.com/) wrapper. So the Transformer API is same as [official documentation](https://fractal.thephpleague.com/transformers/).

> **NOTE** instead of `transform` method you **MUST** implement `data` method. You may implement `links` method, if there are any links to be append to links object of the resource json object.   

This package comes with `TransformerAbstract` class. You **MUST** extend when you are implementing Transformer classes.

```php
use Sarala\Transformer\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'author',
        'tags',
        'comments',
    ];

    public function data(Post $post): array
    {
        return [
            'id' => (int) $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'subtitle' => $post->subtitle,
            'body' => $post->body,
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->created_at->toIso8601String(),
            'published_at' => optional($post->published_at)->toIso8601String(),
        ];
    }

    public function includeAuthor(Post $post)
    {
        return $this->item($post->author, new UserTransformer(), 'users');
    }

    public function includeTags(Post $post)
    {
        return $this->collection($post->tags, new TagTransformer(), 'tags');
    }

    public function includeComments(Post $post)
    {
        return $this->collection($post->comments, new CommentTransformer(), 'comments');
    }
}
```

### Append Links

[Hypermedia Controls](https://martinfowler.com/articles/richardsonMaturityModel.html) is one of the most important things that you must implement in your REST API. If not, you cannot consider it a REST API. Take a break and read [Build APIs You Won't Hate](https://apisyouwonthate.com/books/build-apis-you-wont-hate.html).

The `links` method will receive a model instance as the first parameter and the authenticated User as the second parameter. It should return a `Links` instance. 

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
