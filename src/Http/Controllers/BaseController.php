<?php

declare(strict_types=1);

namespace Sarala\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Sarala\Query\ApiRequestInspector;

class BaseController extends Controller
{
    protected Manager $manager;
    private Request $request;

    public function __construct(Manager $manager, Request $request)
    {
        if ($request->filled('include')) {
            $manager->parseIncludes($request->get('include'));
        }

        if ($request->filled('exclude')) {
            $manager->parseExcludes($request->get('exclude'));
        }

        $this->manager = $manager;
        $this->request = $request;
    }

    public function callAction($method, $parameters)
    {
        if ($this->request->filled('include')) {
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

    private function response($data, $status)
    {
        return response()->json($data, $status);
    }
}
