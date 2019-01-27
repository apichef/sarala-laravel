---
sidebarDepth: 0
---

### Middleware

This package comes with `ContentNegotiation` and `ETag` middleware. You can add them inside your `app/Http/Kernel.php` file.

- [ContentNegotiation Middleware](#content-negotiation-middleware)
- [ETag Middleware](#etag-middleware)

```php
protected $routeMiddleware = [
    // ...
    'api_headers' => \Sarala\Http\Middleware\ContentNegotiation::class,
    'etag' => \Sarala\Http\Middleware\ETag::class,
];
```

### Content Negotiation Middleware

> Reference [Content Negotiation Server Responsibilities](https://jsonapi.org/format/#content-negotiation-servers)

You can validate content negotiation header using this middleware:

```php
Route::middleware(['api_headers'])->group(function () {
    // ...
});
```

##### When the `Content-Type` media type client has requested is not supported by the API. Server will response with:

```
Status: 415

{
    "error": {
        "status": "415",
        "title": "Unsupported Media Type"
    }
}
```

##### When the `Accept` media type client has requested is not supported by the API. Server will response with:

```
Status: 406

{
    "error": {
        "status": "406",
        "title": "Not Acceptable"
    }
}
```

### ETag Middleware

> Reference [ETag](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/ETag)

This middleware will:

- Add `ETag` header to the response.
- Compares the `If-None-Match` header sent by client against the current response `ETag` and response accordingly.

You can simply using this middleware:

```php
Route::middleware(['etag'])->group(function () {
    // ...
});
```

##### When the `If-None-Match` header sent by the client matches with the current response, server will response with `304 Not Modified` response.
