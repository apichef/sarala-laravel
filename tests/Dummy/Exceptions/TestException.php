<?php

declare(strict_types=1);

namespace Sarala\Dummy\Exceptions;

use Sarala\Exceptions\ApiException;

class TestException extends ApiException
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
