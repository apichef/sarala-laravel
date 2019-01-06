<?php

declare(strict_types=1);

namespace Sarala\Http\Middleware;

use Closure;
use Sarala\Exceptions\UnsupportedMediaTypeHttpException;

class ContentNegotiation
{
    public function handle($request, Closure $next)
    {
        if (! str_contains($request->header('Content-Type'), 'application/vnd.api+json')) {
            throw new UnsupportedMediaTypeHttpException();
        }

        return $next($request);
    }
}
