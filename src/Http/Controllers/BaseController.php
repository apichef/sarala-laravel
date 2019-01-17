<?php

declare(strict_types=1);

namespace Sarala\Http\Controllers;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use League\Fractal\Resource\Item;
use Illuminate\Routing\Controller;
use Sarala\Query\ApiRequestInspector;
use League\Fractal\Resource\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class BaseController extends Controller
{
    protected $fractal;

    public function __construct(Manager $manager, Request $request)
    {
        if ($request->filled('fields')) {
            $manager->parseFieldsets($request->get('fields'));
        }

        if ($request->filled('include')) {
            $manager->parseIncludes($request->get('include'));
        }

        if ($request->filled('exclude')) {
            $manager->parseExcludes($request->get('exclude'));
        }

        $this->fractal = $manager;
    }

    public function callAction($method, $parameters)
    {
        (new ApiRequestInspector($parameters))->inspect();

        return call_user_func_array([$this, $method], $parameters);
    }

    public function responseItem($object, $transformer, $resourceKey = null): JsonResponse
    {
        $resource = new Item($object, $transformer, $resourceKey);

        return $this->response($this->fractal->createData($resource)->toArray());
    }

    public function responseCollection($collection, $transformer, $resourceKey = null): JsonResponse
    {
        $resource = new Collection($collection, $transformer, $resourceKey);

        if ($collection instanceof LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }

        return $this->response($this->fractal->createData($resource)->toArray());
    }

    private function response($data = [], $status = 200, $headers = [])
    {
        return response()->json($data, $status, array_merge(config('sarala.response_headers'), $headers));
    }
}
