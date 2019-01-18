---
sidebarDepth: 0
---

### Using Middleware

This package comes with `ContentNegotiation` middleware. You can add them inside your `app/Http/Kernel.php` file.

> Reference [Content Negotiation Server Responsibilities](https://jsonapi.org/format/#content-negotiation-servers)

```php
protected $routeMiddleware = [
    // ...
    'api_headers' => \Sarala\Http\Middleware\ContentNegotiation::class,
];
```

Then you can protect your routes using middleware rules:

```php
Route::middleware(['api_headers'])->group(function () {
    // ...
});
```

### TODO:

Currently it validates only `Content-Type` header. `Accept` header validation needs to be implemented. 
