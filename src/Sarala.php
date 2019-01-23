<?php

declare(strict_types=1);

namespace Sarala;

class Sarala
{
    private static $instance;

    private $handlers;

    private $handler;

    private $supportedMediaTypes;

    private function __construct()
    {
        $this->handlers = collect(config('sarala.handlers'));

        $this->handler = $this->handlers
            ->where('media_type', request()->header('Accept'))
            ->first();

        $this->supportedMediaTypes = $this->handlers
            ->pluck('media_type')
            ->all();
    }

    public static function resolve(): Sarala
    {
        if (! isset(self::$instance)) {
            self::$instance = new Sarala();
        }

        return self::$instance;
    }

    public function getSerializer()
    {
        return app()->make($this->handler['serializer']);
    }

    public function getSupportedMediaTypes()
    {
        return $this->supportedMediaTypes;
    }
}
