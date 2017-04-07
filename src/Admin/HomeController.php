<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\AdminControllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\view;

class HomeController extends Controller
{
    /**
     * 分享管理后台入口.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show()
    {
        return view('admin', [
            'base_url' => route('music:admin'),
            'csrf_token' => csrf_token(),
        ]);
    }
}
