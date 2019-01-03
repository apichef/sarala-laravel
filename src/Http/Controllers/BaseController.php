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

class BaseController extends Controller
{
    protected $fractal;
    private $headers = ['Content-Type' => 'application/vnd.api+json'];

    public function __construct(Manager $manager, Request $request)
    {
        if ($request->filled('include')) {
            $manager->parseIncludes($request->get('include'));
        }

        if ($request->filled('exclude')) {
            $manager->parseExcludes($request->get('exclude'));
        }

        $this->fractal = $manager;
    }

    protected function responseItem($object, $transformer, $resourceKey = null): JsonResponse
    {
        $resource = new Item($object, $transformer, $resourceKey);
        $data = $this->fractal->createData($resource)->toArray();

        return response()->json($data, 200, $this->headers);
    }

    protected function responseCollection($collection, $transformer, $resourceKey = null): JsonResponse
    {
        $resource = new Collection($collection, $transformer, $resourceKey);

        if ($collection instanceof LengthAwarePaginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }

        $data = $this->fractal->createData($resource)->toArray();

        return response()->json($data, 200, $this->headers);
    }
}
