<?php

declare(strict_types=1);

namespace Sarala\Exceptions;

class NotAcceptableMediaTypeHttpException extends ApiException
{
    /**
     * Get the HTTP status code applicable to this problem.
     */
    public function status(): int
    {
        return 406;
    }

    /**
     * Get short, human-readable summary of the problem.
     */
    public function title(): string
    {
        return 'Not Acceptable';
    }
}
