---
sidebarDepth: 0
---

### Exception

This package comes with `ApiException` class. You **MUST** extend this if you are implementing exception classes.

> Reference: [Error Objects](https://jsonapi.org/format/#error-objects)

You **MUST** implement `status` and `title` methods. Implementing the other methods will make your REST API perfect by providing more helpful information.:v: 

```php
use Sarala\Exceptions\ApiException;

class MyException extends ApiException
{
    /**
     * Get unique identifier for this particular occurrence
     * of the problem.
     */
    public function id(): ?string
    {
        return 'ex001';
    }

    /**
     * Get the HTTP status code applicable to this problem.
     */
    public function status(): int
    {
        return 499;
    }

    /**
     * Get application-specific error code.
     */
    public function code(): ?string
    {
        return 'EX:SARALA:001';
    }

    /**
     * Get short, human-readable summary of the problem.
     */
    public function title(): string
    {
        return 'Test Exception Title';
    }

    /**
     * Get human-readable explanation specific to this
     * occurrence of the problem.
     */
    public function detail(): ?string
    {
        return 'More details about this error.';
    }

    /**
     * Get the URI that yield further details about this
     * particular occurrence of the problem.
     */
    public function href(): ?string
    {
        return 'http://localhost/debug-exception/ex001';
    }

    /**
     * Get associated resources, which can be dereferenced
     * from the request document.
     */
    public function links(): array
    {
        return [
            'foo' => 'http://localhost/foos/1',
            'bar' => 'http://localhost/bars/1',
        ];
    }

    /**
     * Get relative path to the relevant attribute within
     * the associated resource(s).
     */
    public function path(): ?string
    {
        return 'foo.bar';
    }
}
```
```
Status: 499
Content-Type: application/vnd.api+json

{
    "error": {
        "id": "ex001",
        "code": "EX:SARALA:001",
        "status": "499",
        "title": "Test Exception Title",
        "detail": "More details about this error.",
        "links": {
            "foo": "http://localhost/foos/1",
            "bar": "http://localhost/bars/1"
        },
        "href": "http://localhost/debug-exception/ex001",
        "path": "foo.bar"
    }
}
```
