<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicCollection;

class MusicCollectionController extends Controller
{
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

        return response()->json($specials, 200);
    }
}