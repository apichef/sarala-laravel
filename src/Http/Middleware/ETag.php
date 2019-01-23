<?php

declare(strict_types=1);

namespace Sarala\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ETag
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('get')) {
            $etag = md5($response->getContent());
            $requestEtag = str_replace('"', '', $request->getETags());

            if ($requestEtag && $requestEtag[0] == $etag) {
                $response->setNotModified();
            }

            $response->setEtag($etag);
        }

        return $response;
    }
}
