<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicPaidController extends Controller
{
    /**
     * Get the specials that the user has purchased.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial $musicSpecial
     * @return mixed
     */
    public function specials(Request $request, MusicSpecial $musicSpecial)
    {
        $user = $request->user();
        $limit = $request->query('limit', 20);
        $max_id = $request->query('max_id', 0);
        $specials = $musicSpecial->with(['storage','paidNode'])->select('music_specials.*')->join('paid_nodes', function ($join) {
            return $join->on('paid_nodes.raw', '=', 'music_specials.id')->where('channel', 'music');
        })
        ->join('paid_node_users', function($join) use ($user) {
            return $join->on('paid_nodes.id', '=', 'paid_node_users.node_id')->where('paid_node_users.user_id', $user->id);
        })
        ->when($max_id, function ($query) use ($max_id) {
            return $query->where('music_specials.id', '<', $max_id);
        })
        ->orderBy('music_specials.id', 'desc')
        ->take($limit)
        ->get();

        $specials = $musicSpecial->getConnection()->transaction(function () use ($specials, $user) {
            return $specials->map(function ($special) use ($user) {
                $special->has_collect = $special->hasCollected($user->id);
                $special = $special->formatPaidNode($user->id);
                return $special;
            });
        });

        return response()->json($specials)->setStatusCode(200);
    }

    /**
     * Get the musics that the user has purchased.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music $musicModel
     * @return mixed
     */
    public function musics(Request $request, Music $musicModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 20);
        $max_id = $request->query('max_id', 0);
        $musics = $musicModel->select('musics.*')
        ->with(['singer' => function ($query) {
            $query->with('cover');
        }])
        ->join('paid_nodes', function ($join) {
            return $join->on('paid_nodes.raw', '=', 'musics.storage')->where('channel', 'file');
        })
        ->join('paid_node_users', function($join) use ($user) {
            return $join->on('paid_nodes.id', '=', 'paid_node_users.node_id')->where('paid_node_users.user_id', $user->id);
        })
        ->when($max_id, function ($query) use ($max_id) {
            return $query->where('musics.id', '<', $max_id);
        })
        ->orderBy('musics.id', 'desc')
        ->take($limit)
        ->get();

        return response()->json($musicModel->getConnection()->transaction(function () use ($musics, $user) {
            return $musics->map(function ($music) use ($user) {
                $music->has_like = $music->liked($user);
                $music->formatStorage($user->id);

                return $music;
            });
        }))->setStatusCode(200);
    }
}