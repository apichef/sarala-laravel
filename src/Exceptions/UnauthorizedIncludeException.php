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

    public function status(): int
    {
        return $this->getCode();
    }

    public function title(): string
    {
        return 'Unacceptable include';
    }

    public function detail(): ?string
    {
        return $this->getMessage();
    }
}
