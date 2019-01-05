<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Controllers;

use Sarala\Http\Controllers\BaseController;

class NonApiRequestController extends BaseController
{
    public function show()
    {
        return response()->json(['status' => 'you can not see me']);
    }
}
