<?php

declare(strict_types=1);

namespace Sarala\Query;

class QueryParam
{
    /** @var string $field */
    private $field = null;

    /** @var ParamBag $params */
    private $params = null;

    public function __construct(array $sections)
    {
        $this->field = $sections[0];
        $this->setParams($sections);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getParams(): ParamBag
    {
        return $this->params;
    }

    protected function setParams(array $sections): void
    {
        $params = [];

        for ($i = 1; $i < count($sections); $i++) {
            $paramSections = explode('(', $sections[$i]);
            $params[$paramSections[0]] = explode('|', str_replace(')', '', $paramSections[1]));
        }

        $this->params = new ParamBag($params);
    }
}
