<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicCollectionController extends Controller
{
    /**
     * 用户收藏列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $musicSpecialModel
     * @return json
     */
    public function list(Request $request, MusicSpecial $musicSpecialModel)
    {
        $limit = $request->input('limit', 20);
        $max_id = $request->input('max_id');
        $user = $request->user();
        $specials = $musicSpecialModel->join('music_collections', function ($join) use ($user) {
            return $join->on('music_collections.special_id', '=', 'music_specials.id')->where('user_id', $user->id);
        })->when($max_id, function ($query) use ($max_id) {
            return $query->where('id', '<', $max_id);
        })
        ->with(['storage', 'paidNode'])
        ->select('music_specials.*')->limit($limit)->get();

        $specials = $musicSpecialModel->getConnection()->transaction(function () use ($specials, $user) {
            return $specials->map(function ($special) use ($user) {
                return $special->formatPaidNode($user->id);
            });
        });

        return response()->json($specials, 200);
    }

    /**
     * 收藏专辑.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $special
     * @return json
     */
    public function store(Request $request, MusicSpecial $special)
    {
        $user = $request->user()->id;
        if ($special->hasCollected($user)) {
            return response()->json([
                'message' => ['已收藏该专辑'],
            ])->setStatusCode(400);
        }
       
        DB::transaction(function() use ($special, $user) {
            $special->collections()->create(['user_id' => $user]);
            $special->increment('collect_count');
        });

        $cacheKey = sprintf('music-special-collected:%s,%s', $special->id, $user);
        Cache::forget($cacheKey);

        return response()->json([
            'message' => ['收藏成功'],
        ])->setStatusCode(201);
    }

    /**
     * 取消收藏专辑.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $special
     * @return json
     */
    public function delete(Request $request, MusicSpecial $special)
    {
        $user = $request->user()->id;
        if (!$special->hasCollected($user)) {
            return response()->json([
                'message' => ['未收藏该专辑'],
            ])->setStatusCode(400);
        }

        DB::transaction(function() use ($user, $special) {
            $special->collections()->delete();
            $special->decrement('collect_count');
        });
        
        $cacheKey = sprintf('music-special-collected:%s,%s', $special->id, $user);
        Cache::forget($cacheKey);
        
        return response()->json()->setStatusCode(204);
    }
}