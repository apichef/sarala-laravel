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
    protected $manager;

    public function __construct(Manager $manager, Request $request)
    {
        if ($request->filled('include')) {
            $manager->parseIncludes($request->get('include'));
        }

        if ($request->filled('exclude')) {
            $manager->parseExcludes($request->get('exclude'));
        }

        $this->manager = $manager;
    }

    public function callAction($method, $parameters)
    {
        if (request()->filled('include')) {
            (new ApiRequestInspector($parameters))->inspect();
        }

        return parent::callAction($method, $parameters);
    }

    public function responseItem($object, $transformer, $resourceKey, $status = 200): JsonResponse
    {
        $resource = new Item($object, $transformer, $resourceKey);

        return $this->response($this->manager->createData($resource)->toArray(), $status);
    }

    public function responseCollection($collection, $transformer, $resourceKey, $status = 200): JsonResponse
    {
        $resource = new Collection($collection, $transformer, $resourceKey);

        if ($collection instanceof LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }

        return $this->response($this->manager->createData($resource)->toArray(), $status);
    }

    private function response($data = [], $status)
    {
        return response()->json($data, $status);
    }
}
