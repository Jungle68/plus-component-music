<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicCommentController extends Controller
{
    /**
     * 添加音乐评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music
     * @param  Comment $comment
     * @return json
     */
    public function store(Request $request, Music $music, Comment $comment)
    {
        $replyUser = intval($request->input('reply_user', 0));
        $body = $request->input('body');
        $user = $request->user();

        $comment->user_id = $user->id;
        $comment->reply_user = $replyUser;
        $comment->target_user = 0;  //音乐暂为后台上传
        $comment->body = $body;

        $music->getConnection()->transaction(function () use ($music, $comment, $user) {
        	$music->comments()->save($comment);
        	$music->increment('comment_count', 1);
        	$music->musicSpecials()->increment('comment_count', 1);
        	$user->extra()->firstOrCreate([])->increment('comments_count', 1);
        });

        if ($replyUser) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            $message = sprintf('%s 回复了您的评论', $user->name);
            $replyUser->sendNotifyMessage('music:comment-reply', $message, [
                'music' => $music,
                'user' => $user,
            ]);
        }

        return response()->json([
        	'message' => ['操作成功'],
        	'comment' => $comment,
    	])->setStatusCode(201);
    }

    /**
     * 音乐评论列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music
     * @return json
     */
    public function list(Request $request, Music $music)
    {
    	$max_id = $request->input('max_id');
    	$limit = $request->input('limit', 15);
    	$comments = $music->comments()->when($max_id, function ($query) use ($max_id) {
    		return $query->where('id', '<', $max_id);
    	})->limit($limit)->orderBy('id', 'desc')->get();

    	return response()->json($comments, 200);
    }

    /**
     * 删除音乐评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music
     * @param  Comment $comment
     * @return json
     */
    public function delete(Request $request, Music $music, Comment $comment)
    {
        $user = $request->user();
        if ($comment->user_id !== $user->id) {
            return $response->json(['message' => ['没有权限']], 403);
        }

        $music->getConnection()->transaction(function () use ($user, $music, $comment) {
            $music->decrement('comment_count', 1);
            $music->musicSpecials()->decrement('comment_count', 1);
            $user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return response()->json()->setStatusCode(204);
    }

    /**
     * 删除专辑评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $special
     * @param  Comment      $comment
     * @return json
     */
    public function specialDelete(Request $request, MusicSpecial $special, Comment $comment)
    {
        $user = $request->user();
        if ($comment->user_id !== $user->id) {
            return $response->json(['message' => ['没有权限']], 403);
        }

        $special->getConnection()->transaction(function () use ($user, $special, $comment) {
            $special->decrement('comment_count', 1);
            $user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return response()->json()->setStatusCode(204);
    }

    /**
     * 专辑评论列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $special
     * @return json
     */
    public function specialList(Request $request, MusicSpecial $special)
    {
    	$max_id = $request->input('max_id');
    	$limit = $request->input('limit', 15);
    	$comments = $special->comments()->orWhere(function ($query) use ($special) {
            return $query->where('commentable_type', 'musics')->whereIn('commentable_id', $special->musics->pluck('id'));
        })->when($max_id, function ($query) use ($max_id) {
    		return $query->where('id', '<', $max_id);
    	})->limit($limit)->orderBy('id', 'desc')->get();

    	return response()->json($comments, 200);
    }

    /**
     * 添加专辑评论.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  MusicSpecial $special
     * @param  Comment      $comment
     * @return json
     */
    public function specialStore(Request $request, MusicSpecial $special, Comment $comment)
    {
        $replyUser = intval($request->input('reply_user', 0));
        $body = $request->input('body');
        $user = $request->user();

        $comment->user_id = $user->id;
        $comment->reply_user = $replyUser;
        $comment->target_user = 0;  //音乐暂为后台上传
        $comment->body = $body;

        $special->getConnection()->transaction(function () use ($special, $comment, $user) {
        	$special->comments()->save($comment);
        	$special->increment('comment_count', 1);
        	$user->extra()->firstOrCreate([])->increment('comments_count', 1);
        });

        if ($replyUser) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            $message = sprintf('%s 回复了您的评论', $user->name);
            $replyUser->sendNotifyMessage('music:special-comment-reply', $message, [
                'music-special' => $special,
                'user' => $user,
            ]);
        }

        return response()->json([
        	'message' => ['操作成功'],
        	'comment' => $comment,
    	])->setStatusCode(201);
    }
}