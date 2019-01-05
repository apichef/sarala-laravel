<?php

declare(strict_types=1);

namespace Sarala\Query;

use Sarala\Exceptions\ApiRequestContractNotImplementedException;
use Sarala\Exceptions\UnauthorizedIncludeException;
use Sarala\Http\Requests\ApiRequestAbstract;

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
        $this->request->includes()->each(function (QueryParam $include) {
            if (! $this->isIncludeAllowed($include)) {
                throw new UnauthorizedIncludeException($include->getField(), $this->request->allowedIncludes());
            }
        });
    }

    private function isIncludeAllowed(QueryParam $include): bool
    {
        foreach ($this->request->allowedIncludes() as $allowedInclude) {
            if (starts_with($allowedInclude, $include->getField())) {
                return true;
            }
        }

        return false;
    }
}
