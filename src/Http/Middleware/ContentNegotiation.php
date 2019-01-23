<?php

declare(strict_types=1);

namespace Sarala\Http\Middleware;

use Closure;
use Sarala\Sarala;
use Sarala\Exceptions\UnsupportedMediaTypeHttpException;
use Sarala\Exceptions\NotAcceptableMediaTypeHttpException;

class ContentNegotiation
{
    public function handle($request, Closure $next)
    {
        $supportedMediaTypes = Sarala::resolve()->getSupportedMediaTypes();

        if (! in_array($request->header('Content-Type'), $supportedMediaTypes)) {
            throw new UnsupportedMediaTypeHttpException();
        }

        if (! $request->accepts($supportedMediaTypes)) {
            throw new NotAcceptableMediaTypeHttpException();
        }

        $response = $next($request);

        $response->header('Content-Type', $request->header('Accept'));

        return $response;
    }
}
