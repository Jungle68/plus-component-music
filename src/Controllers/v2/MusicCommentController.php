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

    public function list(Request $request, Music $music)
    {
    	$max_id = $request->input('max_id');
    	$limit = $request->input('limit', 15);
    	$comments = $music->comments()->when($max_id, function ($query) use ($max_id) {
    		return $query->where('id', '<', $max_id);
    	})->limit($limit)->orderBy('id', 'desc')->get();

    	return response()->json($comments, 200);
    }

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

    public function specialList(Request $request, MusicSpecial $special)
    {
    	$max_id = $request->input('max_id');
    	$limit = $request->input('limit', 15);
    	$comments = $special->comments()->when($max_id, function ($query) use ($max_id) {
    		return $query->where('id', '<', $max_id);
    	})->limit($limit)->orderBy('id', 'desc')->get();

    	return response()->json($comments, 200);
    }

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
}