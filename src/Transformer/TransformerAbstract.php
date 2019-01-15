<?php

declare(strict_types=1);

namespace Sarala\Transformer;

use Sarala\Links;
use League\Fractal\TransformerAbstract as BaseTransformerAbstract;

abstract class TransformerAbstract extends BaseTransformerAbstract
{
    public function transform($data)
    {
        $user = auth(config('sarala.guard'))->user();

        return array_merge($this->data($data), ['links' => $this->links($data, $user)->all()]);
    }

    public function links($model, $user = null): Links
    {
        return Links::make();
    }
}
