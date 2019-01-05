<?php

namespace Sarala\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sarala\Http\Requests\ApiRequestAbstract;

class ApiRequestContractNotImplementedException extends Exception
{
    public function render(Request $request): Response
    {
        return response()->json([
            'status' => '500',
            'title' => 'API Request should extend ' . ApiRequestAbstract::class,
        ], 500);
    }
}
