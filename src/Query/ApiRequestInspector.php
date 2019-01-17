<?php

declare(strict_types=1);

namespace Sarala\Query;

use Sarala\Http\Requests\ApiRequestAbstract;
use Sarala\Exceptions\UnauthorizedIncludeException;
use Sarala\Exceptions\ApiRequestContractNotImplementedException;

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
            if ($parameter instanceof ApiRequestAbstract) {
                $this->request = $parameter;
            }
        }

        if (is_null($this->request)) {
            throw new ApiRequestContractNotImplementedException();
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
            if (starts_with($allowedInclude, $field)) {
                return true;
            }
        }

        return false;
    }
}
