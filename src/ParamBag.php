<?php

declare(strict_types=1);

namespace Sarala;

use League\Fractal\ParamBag as BaseParamBag;

class ParamBag extends BaseParamBag
{
    public function has($key): bool
    {
        return $this->__isset($key);
    }
}
