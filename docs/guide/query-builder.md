### Query Builder

As we are implementing a REST API according to {json:api} specification, you may receive very complex fetch requests at your api endpoint. The purpose of `QueryBuilder` object is translating request to database query object. 

To support this, package comes with `QueryBuilderAbstract` class. You **MUST** extend when you are implementing query builder classes and you may implement following as per your requirement.

- [init](#init)
- [filter](#filter)
- [include](#include)
- [sort](#sort)

```php
use Sarala\Query\QueryBuilderAbstract;

class PostShowQuery extends QueryBuilderAbstract
{
    // ...
}
```
Use:
`QueryObject` constructor expects a request object as the only parameter. 
```php
$postShowQuery = new PostShowQuery($request)
// ...
$postShowQuery->fetch() // execute and returns result
$postShowQuery->fetchFirst() // execute and returns first from collection
```

### init
You must implements `init` method on the query object class. This method should return a `Illuminate\Database\Eloquent\Builder` object as the starting point of the query.

```
GET /posts/{post}
```

```php
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostShowQuery extends QueryBuilderAbstract
{
    protected function init(): Builder
    {
        return Post::where('id', $this->request->route('post')->id);
    }
}
```

Depending on your requirement you **MAY** implement `filter`, `include` and `orderBy` methods.

### filter

When you need to filter resources on a collection endpoint, you may allow it by implementing `filter` method on the `QueryBuilder` class. `filter` will receive a [QueryParamBag](/guide/query-param-bag.md) object as the only parameter.

> Reference: 
> - [Filtering](https://jsonapi.org/format/#fetching-filtering)
> - [Recommendations](https://jsonapi.org/recommendations/#filtering)

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    // ...
    
    public function scopeComposedBy(Builder $builder, User $user)
    {
        return $builder->where('author_id', $user->id);
    }
}
```

```
GET /posts?filter[my]
```

```php
use App\Post;
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostCollectionQuery extends QueryBuilderAbstract
{
    // ...

    protected function filter(QueryParamBag $filters)
    {
        $this->query
            ->when($filters->has('my'), function ($query) {
                $query->composedBy($this->request->user());
            });
    }
}
```

### include

When you need to include related resources, you may allow it by implementing `include` method on the `QueryBuilder` class. `include` will receive a [QueryParamBag](/guide/query-param-bag.md) object as the only parameter.

> Reference: [Inclusion of Related Resources](https://jsonapi.org/format/#fetching-includes)

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    // ...
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
```

```php
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // ...
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

```
GET /posts/{post}?include=comments.author
```

```php
use App\Post;
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostShowQuery extends QueryBuilderAbstract
{
    // ...
    
    protected function include(QueryParamBag $includes)
    {
        $this->query
            ->when($includes->has('comments.author'), function ($query) {
                $query->with('comments.user');
            });
    }
}
```

This can get ugly pretty soon, so we have [include helpers](/guide/include-helpers.md) to make it clean.

### sort

When you need to sort a resources collection, you may allow it by implementing `sort` method on the `QueryBuilder` class. `sort` method **MUST** return an array of sortable fields. It will automatically identify sort direction and do the needful.

> Reference: [Sorting](https://jsonapi.org/format/#fetching-sorting)

```
GET /posts?sort=-published_at
```

```php
use App\Post;
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostCollectionQuery extends QueryBuilderAbstract
{
    // ...

    protected function sort()
    {
        return [
            'published_at',
            'comments.created_at',
        ];
    }
}
```
