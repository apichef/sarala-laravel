<?php

declare(strict_types=1);

namespace Sarala;

use League\Fractal\Serializer\DataArraySerializer;

class Sarala
{
    private $handlers;

    private $handler;

    private $supportedMediaTypes;

    public function __construct()
    {
        $this->handlers = collect(config('sarala.handlers'));

        $this->handler = $this->handlers
            ->where('media_type', request()->header('Accept'))
            ->first();

        $this->supportedMediaTypes = $this->handlers
            ->pluck('media_type')
            ->all();
    }

    public function getSerializer()
    {
        $serializer = is_null($this->handler) ? DataArraySerializer::class : $this->handler['serializer'];

        return app()->make($serializer);
    }

    public function getSupportedMediaTypes()
    {
        return $this->supportedMediaTypes;
    }
}
