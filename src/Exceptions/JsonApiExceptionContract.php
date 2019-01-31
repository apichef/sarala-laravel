<?php

declare(strict_types=1);

namespace Sarala\Exceptions;

interface JsonApiExceptionContract
{
    /**
     * Get unique identifier for this particular occurrence
     * of the problem.
     */
    public function id(): string;

    /**
     * Get the HTTP status code applicable to this problem.
     */
    public function status(): int;

    /**
     * Get application-specific error code.
     */
    public function code(): string;

    /**
     * Get short, human-readable summary of the problem.
     */
    public function title(): string;

    /**
     * Get human-readable explanation specific to this
     * occurrence of the problem.
     */
    public function detail(): string;

    /**
     * Get the URI that yield further details about this
     * particular occurrence of the problem.
     */
    public function href(): string;

    /**
     * Get associated resources, which can be dereferenced
     * from the request document.
     */
    public function links(): array;

    /**
     * Get relative path to the relevant attribute within
     * the associated resource(s).
     */
    public function path(): string;

    /**
     * Get non-standard meta-information about the error.
     */
    public function meta(): array;
}
