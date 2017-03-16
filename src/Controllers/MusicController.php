<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecialLink;

class MusicController extends Controller
{
	public function getMusicInfo(Request $request, $music_id)
	{
		$musicInfo = Music::where('id', $music_id)->with(['singer' => function ($query) {
            $query->with('cover');
        }])->first();
		if (!$musicInfo) {
           return response()->json([
                'status' => false,
                'code' => 8002,
                'message' => '歌曲不存在或已被删除'
            ])->setStatusCode(404); 
		}

		Music::where('id', $music_id)->increment('taste_count'); // 歌曲增加播放数量
		MusicSpecial::whereIn('id', MusicSpecialLink::where('music_id', $music_id)->pluck('special_id'))->increment('taste_count'); // 相应专辑增加播放数量

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data' => $musicInfo
        ])->setStatusCode(200);
	}
}