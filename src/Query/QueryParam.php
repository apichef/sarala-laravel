<?php

declare(strict_types=1);

namespace Sarala\Query;

class QueryParam
{
    /** @var string $field */
    private $field = null;

    /** @var ParamBag $params */
    private $params = null;

    public function __construct(string $field, array $params)
    {
        $this->field = $field;
        $this->params = new ParamBag($params);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getParams(): ParamBag
    {
        return $this->params;
    }
}
