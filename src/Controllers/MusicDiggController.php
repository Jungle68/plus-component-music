<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicDigg;
use Zhiyi\Plus\Models\UserDatas;

class MusicDiggController extends Controller
{
	/**
	 * 点赞一个音乐
	 * 
	 * @author bs<414606094@qq.com>
	 * @param  Request $request [description]
	 * @param  int     $Music_id [description]
	 * @return [type]           [description]
	 */
	public function diggMusic(Request $request, int $music_id)
	{
		$music = Music::find($music_id);
		if (!$music) {
            return response()->json(static::createJsonData([
                'code' => 8002,
            ]))->setStatusCode(404);
		}
		$musicdigg['user_id'] = $request->user()->id;
		$musicdigg['music_id'] = $music_id;
		if (MusicDigg::where($musicdigg)->first()) {
            return response()->json(static::createJsonData([
            	'code' => 8003,
                'status' => false,
                'message' => '已赞过该歌曲',
            ]))->setStatusCode(400);
		}
		
		MusicDigg::create($musicdigg);

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '点赞成功',
        ]))->setStatusCode(201);
	}

	/**
	 * 取消点赞一个动态
	 * 
	 * @author bs<414606094@qq.com>
	 * @param  Request $request [description]
	 * @param  int     $Music_id [description]
	 * @return [type]           [description]
	 */
	public function cancelDiggMusic(Request $request, int $music_id)
	{
		$music = Music::find($music_id);
		if (!$music) {
            return response()->json(static::createJsonData([
                'code' => 8002,
            ]))->setStatusCode(404);
		}
		$musicdigg['user_id'] = $request->user()->id;
		$musicdigg['music_id'] = $music_id;
		if (!MusicDigg::where($musicdigg)->first()) {
            return response()->json(static::createJsonData([
            	'code' => 8004,
                'status' => false,
                'message' => '未对该歌曲点赞',
            ]))->setStatusCode(400);
		}

		MusicDigg::where($musicdigg)->delete();
		
        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消点赞成功',
        ]))->setStatusCode(201);
	}
}