---
sidebarDepth: 0
---

### Installation

 You can install the package via composer:
``` bash
$ composer require sarala-io/sarala
```

You can optionally publish the config file with:
``` bash
$ php artisan vendor:publish --provider="Sarala\JsonApiServiceProvider" --tag="config"
```

When published, [the `config/sarala.php` config](https://github.com/sarala-io/sarala-laravel/blob/master/config/sarala.php) file contains:

```php
return [
    /*
     * League\Fractal\Serializer\JsonApiSerializer will use this value to
     * as a prefix for generated links. Set to `null` to disable this.
     */
    'base_url' => url('/api'),

    /*
     * This guard will be used when fetching the authenticated user to pass
     * to the links method on the transformer.
     */
    'guard' => null,

    /*
     * When sending back serialized data to the client this header(s) will
     * be appended to the response.
     */
    'response_headers' => [
        'Content-Type' => 'application/vnd.api+json',
    ],
];
```
