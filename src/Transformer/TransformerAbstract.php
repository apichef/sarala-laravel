<?php

declare(strict_types=1);

namespace Sarala\Transformer;

use League\Fractal\TransformerAbstract as BaseTransformerAbstract;

abstract class TransformerAbstract extends BaseTransformerAbstract
{
    public function transform($data)
    {
        return array_merge($this->filterFields($this->data($data)), ['links' => $this->links($data)]);
    }

    protected function filterFields(array $data)
    {
        $resourceName = $this->getCurrentScope()->getResource()->getResourceKey();

        if (request()->filled('fields') && ! is_null(request()->input("fields.{$resourceName}"))) {
            return array_only($data, explode(',', request()->input("fields.{$resourceName}")));
        }

        return $data;
    }
}
