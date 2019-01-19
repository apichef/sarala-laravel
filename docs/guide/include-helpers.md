### Include Helpers

There may be many includes for a resource, depending on their relationships. Additionally, we may also need to apply conditions against these includes.

```
GET /posts/{post}?include=author,comments:limit(5):sort(created_at|desc),tags
```

```php
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostShowQuery extends QueryBuilderAbstract
{
    // ...
    
    protected function include(QueryParamBag $includes)
    {
        $this->query
            ->when($includes->has('author'), function ($query) {
                $query->with('author');
            })
            ->when($includes->has('tags'), function ($query) {
                $query->with('tags');
            })
            ->when($includes->has('comments'), function ($query) {
                $query->with('comments');
            })
            ->when($includes->has('comments.author'), function ($query) {
                $query->with('comments.user');
            })
            ->when(! $includes->isEmpty('comments'), function (Builder $query) use ($includes) {
                $query->with(['comments' => function ($query) use ($includes) {
                    $query
                    ->when($includes->has('comments.limit'), function ($query) use ($includes) {
                        list($limit) = $includes->get('comments.limit');
                        $query->limit($limit);
                    })
                    ->when($includes->has('comments.sort'), function ($query) use ($includes) {
                        list($column, $direction) = $includes->get('comments.sort');
                        $query->orderBy($column, $direction);
                    });
                }]);
            });
    }
}
```

And yes, it may even become more complicated than this. 

### exact

You may use `exact` method when the include and model relation name is exactly the same. It accepts a string or list of relationships as a array of string as the only parameter.

```php
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostShowQuery extends QueryBuilderAbstract
{
    // ...
    
    protected function include(QueryParamBag $includes)
    {
        $this->exact([
            'author',
            'tags',
            'comments',
        ]);
    }
}
```

### alias

You may use `alias` method when the include and model relation name is **NOT** exactly the same or when you need to play with include sub parameters. It accepts include name as the first parameter and the relationship it should be mapped to as a string or callback.

```php
use Sarala\Query\QueryParamBag;
use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Database\Eloquent\Builder;

class PostShowQuery extends QueryBuilderAbstract
{
    // ...
    
    protected function include(QueryParamBag $includes)
    {
        $this->exact([
            'author',
            'tags',
        ])
        ->alias('comments.author', 'comments.user')
        ->alias('comments', function (Builder $query) use ($includes) {
            $query->with(['comments' => function ($query) use ($includes) {
                $query
                    ->when($includes->has('comments.limit'), function ($query) use ($includes) {
                        list($limit) = $includes->get('comments.limit');
                        $query->limit($limit);
                    })
                    ->when($includes->has('comments.sort'), function ($query) use ($includes) {
                        list($column, $direction) = $includes->get('comments.sort');
                        $query->orderBy($column, $direction);
                    });
            }]);
        });
    }
}
```
