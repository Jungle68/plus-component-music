<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicSpecialController extends Controller
{
    /**
     * 获取专辑列表
     *  
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function getSpecialList(Request $request)
    {
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $specials = MusicSpecial::orderBy('id', 'DESC')
            ->where(function($query) use ($request) {
                if($request->max_id > 0){
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->with('storage')
            ->take($limit)
            ->get();
        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '专辑列表获取成功',
                'data' => $specials
            ])->setStatusCode(200);
    }

    /**
     * 获取专辑详情
     * 
     * @author bs<414606094@qq.com>
     * @param  Request $request    [description]
     * @param  [type]  $special_id [description]
     * @return [type]              [description]
     */
    public function getSpecialInfo(Request $request, $special_id)
    {
        $specialInfo = MusicSpecial::where('id', $special_id)->with(['musics' => function($query) {
            $query->with(['musicInfo' => function($query) {
                $query->with('storage');
            }]);
        }])->first();

        if (!$specialInfo) {
           return response()->json([
                'status' => false,
                'code' => 8001,
                'message' => '专辑不存在或已被删除'
            ])->setStatusCode(404); 
        }

        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '获取成功',
                'data' => $specialInfo
        ])->setStatusCode(200);
    }
}
