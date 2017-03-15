<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecialLink;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment;
use Carbon\Carbon;

class MusicCommentController extends Controller
{
	/**
	 * 查看一条歌曲的评论列表
	 * 
	 * @author bs<414606094@qq.com>
	 * @param  Request $request [description]
	 * @param  int     $music_id [description]
	 * @return [type]           [description]
	 */
	public function getMusicCommentList(Request $request, int $music_id)
	{
		$limit = $request->input('limit', 15);
		$max_id = intval($request->input('max_id'));
		$comments = MusicComment::where('music_id', $music_id)->take($limit)->where(function($query) use ($max_id) {
			if ($max_id > 0) {
				$query->where('id', '<', $max_id);
			}
		})->orderBy('id','desc')->get();	

	    return response()->json(static::createJsonData([
	        'status' => true,
	        'data' => $comments,
	    ]))->setStatusCode(200);
	}

	/**
	 * 查看一个专辑的评论列表
	 * 
	 * @author bs<414606094@qq.com>
	 * @param  Request $request    [description]
	 * @param  int     $special_id [description]
	 * @return [type]              [description]
	 */
	public function getSpecialCommentList(Request $request, int $special_id)
	{
		$limit = $request->input('limit', 15);
		$max_id = intval($request->input('max_id'));
		$comments = MusicComment::where(function ($query) use ($special_id) {
			$query->where('special_id', $special_id)->orwhere(function ($query) use ($special_id) {
				$query->whereIn('music_id', MusicSpecialLink::where('special_id', $special_id)->pluck('music_id'));
			});
		})->take($limit)->where(function($query) use ($max_id) {
			if ($max_id > 0) {
				$query->where('id', '<', $max_id);
			}
		})->orderBy('id','desc')->get();	

	    return response()->json(static::createJsonData([
	        'status' => true,
	        'data' => $comments,
	    ]))->setStatusCode(200);
	}

	/**
	 * 对一条动态或评论进行评论
	 * 
	 * @author bs<414606094@qq.com>
	 * @param  Request $request [description]
	 */
	public function addComment(Request $request, $music_id)
	{	
        $music = Music::with('speciallinks')->find($music_id);
        if (!$music) {
            return response()->json(static::createJsonData([
                'code' => 8002,
            ]))->setStatusCode(403);
        }
        $MusicComment = new MusicComment();
		$MusicComment->user_id = $request->user()->id;
		$MusicComment->music_id = $music_id;
		$MusicComment->reply_to_user_id = $request->reply_to_user_id ?? 0;
		$MusicComment->comment_content = $request->comment_content;
    	
    	$MusicComment->save();
    	Music::where('id', $music->id)->increment('comment_count');//增加评论数量
		MusicSpecial::whereIn('id', $music->speciallinks->pluck('special_id'))->increment('comment_count');//增加评论数量

        return response()->json(static::createJsonData([
                'status' => true,
                'code' => 0,
                'message' => '评论成功',
                'data' => $MusicComment->id
            ]))->setStatusCode(201);
	}

	public function addSpecialComment(Request $request, $special_id)
	{	
        $special = MusicSpecial::find($special_id);
        if (!$special) {
            return response()->json(static::createJsonData([
                'code' => 8001,
            ]))->setStatusCode(403);
        }
        $MusicComment = new MusicComment();
		$MusicComment->user_id = $request->user()->id;
		$MusicComment->special_id = $special_id;
		$MusicComment->reply_to_user_id = $request->reply_to_user_id ?? 0;
		$MusicComment->comment_content = $request->comment_content;
    	
    	$MusicComment->save();
    	MusicSpecial::where('id', $special_id)->increment('comment_count');//增加评论数量

        return response()->json(static::createJsonData([
                'status' => true,
                'code' => 0,
                'message' => '评论成功',
                'data' => $MusicComment->id
            ]))->setStatusCode(201);
	}
}