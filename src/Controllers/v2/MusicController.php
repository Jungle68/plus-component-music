<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecialLink;

class MusicController extends Controller
{
    /**
     * 专辑详情.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music  
     * @return           
     */
    public function show(Request $request, Music $music)
    {
        $uid = $request->user('api')->id ?? 0;
        $music->load(['singer' => function ($query) {
            $query->with('cover');
        }]);

        $music->has_like = $music->liked($uid);
        $music = $music->formatStorage($uid);
        $music->increment('taste_count'); // 歌曲增加播放数量
        MusicSpecial::whereIn('id', MusicSpecialLink::where('music_id', $music->id)->pluck('special_id'))->increment('taste_count'); // 相应专辑增加播放数量

        return response()->json($music)->setStatusCode(200);
    }
}