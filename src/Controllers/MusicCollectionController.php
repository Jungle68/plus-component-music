<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicCollection;

class MusicCollectionController extends Controller
{
    public function addMusicCollection(Request $request, $special_id)
    {
        $musicSpecial = MusicSpecial::find($special_id);
        if (!$musicSpecial) {
            return response()->json(static::createJsonData([
                'code' => 8001,
            ]))->setStatusCode(404);
        }
        $collection['user_id'] = $request->user()->id;
        $collection['special_id'] = $special_id;
        if (MusicCollection::where($collection)->first()) {
            return response()->json(static::createJsonData([
                'code' => 8006,
                'status' => false,
                'message' => '已收藏该专辑',
            ]))->setStatusCode(400);
        }
        
        MusicCollection::create($collection);

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '收藏成功',
        ]))->setStatusCode(201);
    }

    public function delMusicCollection(Request $request, $special_id)
    {
        $musicSpecial = MusicSpecial::find($special_id);
        if (!$musicSpecial) {
            return response()->json(static::createJsonData([
                'code' => 8001,
            ]))->setStatusCode(404);
        }
        $collection['user_id'] = $request->user()->id;
        $collection['special_id'] = $special_id;
        if (!MusicCollection::where($collection)->first()) {
            return response()->json(static::createJsonData([
                'code' => 8007,
                'status' => false,
                'message' => '未收藏该歌曲',
            ]))->setStatusCode(400);
        }

        MusicCollection::where($collection)->delete();
        
        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消收藏成功',
        ]))->setStatusCode(204);
    }
}