### QueryParamBag

- [has](#has)
- [get](#get)
- [isEmpty](#isempty)

`filter` and  `include` methods on the QueryBuilder will receive `QueryParamBag` instance as the only parameter.

This has the knowledge of reading array based and string based request query strings:

```
GET '/posts?include=comments:limit(5):sort(created_at|desc),author'
```
or
```
GET '/posts?include[comments][limit]=5&include[comments][sort]=created_at|desc&include[author]'
```

Following examples are based on above request.

```php
$queryParam = new QueryParamBag($request, 'include');
```

##### has

```php
$queryParam->has('comments'); // true
$queryParam->has('comments.limit'); // true
$queryParam->has('comments.sort'); // true
$queryParam->has('comments.crap'); // false
$queryParam->has('author'); // true
```

##### get

```php
$queryParam->get('comments.limit'); // [5]
$queryParam->get('comments.sort'); // ['created_at', 'desc']
```

##### isEmpty

```php
$queryParam->isEmpty('comments'); // false
$queryParam->isEmpty('author'); // true
```
