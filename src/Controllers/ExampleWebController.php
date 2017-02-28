<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\view;

class ExampleWebController extends Controller
{
    public function example()
    {
        return view('example');
    }

    public function admin()
    {
        return view('example');
    }
}
