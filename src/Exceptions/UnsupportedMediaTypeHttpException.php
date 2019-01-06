<?php

declare(strict_types=1);

namespace Sarala\Exceptions;

class UnsupportedMediaTypeHttpException extends ApiException
{
    /**
     * Get the HTTP status code applicable to this problem.
     */
    public function status(): int
    {
        return 415;
    }

    /**
     * Get short, human-readable summary of the problem.
     */
    public function title(): string
    {
        return 'Unsupported Media Type';
    }
}
