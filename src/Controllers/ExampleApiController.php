<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;

class ExampleApiController extends Controller
{
    public function example()
    {
        return response()->json([
            'message' => 'example',
        ]);
    }
}
