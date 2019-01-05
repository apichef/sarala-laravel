<?php

namespace Sarala\Exceptions;

class UnauthorizedIncludeException extends ApiException
{
    public function __construct(string $unauthorizedInclude, array $allowedIncludes)
    {
        $allowedIncludes = implode(",", $allowedIncludes);
        $message = "{$unauthorizedInclude} is missing in allowed includes. Allowed: {$allowedIncludes}";
        parent::__construct($message, 403);
    }

    /**
     * Get unique identifier for this particular occurrence
     * of the problem.
     */
    public function id(): ?string
    {
        return null;
    }

    /**
     * Get the HTTP status code applicable to this problem.
     */
    public function status(): int
    {
        return $this->getCode();
    }

    /**
     * Get application-specific error code.
     */
    public function code(): ?string
    {
        return null;
    }

    /**
     * Get short, human-readable summary of the problem.
     */
    public function title(): string
    {
        return 'Unacceptable include';
    }

    /**
     * Get human-readable explanation specific to this
     * occurrence of the problem.
     */
    public function detail(): ?string
    {
        return $this->getMessage();
    }

    /**
     * Get the URI that yield further details about this
     * particular occurrence of the problem.
     */
    public function href(): ?string
    {
        return null;
    }

    /**
     * Get associated resources, which can be dereferenced
     * from the request document.
     */
    public function links(): array
    {
        return [];
    }

    /**
     * Get relative path to the relevant attribute within
     * the associated resource(s).
     */
    public function path(): ?string
    {
        return null;
    }
}
