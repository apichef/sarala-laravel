<?php

declare(strict_types=1);

namespace Sarala\Query;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Sarala\Http\Requests\ApiRequestAbstract;
use Sarala\Exceptions\UnauthorizedIncludeException;

class ApiRequestInspector
{
    /** @var ApiRequestAbstract $request */
    private $request = null;

    public function __construct(array $parameters)
    {
        $this->setRequestReceivedAtApiEndpoint($parameters);
    }

    public function inspect()
    {
        $this->sanitizeIncludes();
    }

    private function setRequestReceivedAtApiEndpoint(array $parameters)
    {
        foreach ($parameters as $parameter) {
            if ($parameter instanceof Request) {
                $this->request = $parameter;
            }
        }
    }

    private function sanitizeIncludes()
    {
        $this->request->includes()->each(function ($params, $field) {
            if (! $this->isIncludeAllowed($field)) {
                throw new UnauthorizedIncludeException($field, $this->request->allowedIncludes());
            }
        });
    }

    private function isIncludeAllowed($field): bool
    {
        foreach ($this->request->allowedIncludes() as $allowedInclude) {
            if (Str::startsWith($allowedInclude, $field)) {
                return true;
            }
        }

        return false;
    }
}
