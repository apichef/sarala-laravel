<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Support\Collection;
use League\Fractal\Serializer\DataArraySerializer;

class Sarala
{
    private Collection $handlers;

    private $handler;

    private array $supportedMediaTypes;

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

        return resolve($serializer);
    }

    public function getSupportedMediaTypes(): array
    {
        return $this->supportedMediaTypes;
    }
}
