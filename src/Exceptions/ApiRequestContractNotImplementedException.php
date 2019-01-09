<?php

declare(strict_types=1);

namespace Sarala\Exceptions;

use Sarala\Http\Requests\ApiRequestAbstract;

class ApiRequestContractNotImplementedException extends ApiException
{
    /**
     * Get the HTTP status code applicable to this problem.
     */
    public function status(): int
    {
        return 400;
    }

    /**
     * Get short, human-readable summary of the problem.
     */
    public function title(): string
    {
        return 'API Request should extend '.ApiRequestAbstract::class;
    }
}
