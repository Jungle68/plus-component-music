<?php
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Middleware as MusicMiddleware;

//最新分享列表
// 获取专辑
Route::get('/music/specials', 'MusicSpecialController@getSpecialList');
// 专辑详情
Route::get('/music/specials/{special_id}', 'MusicSpecialController@getSpecialInfo')->where(['special_id' => '[0-9]+']);
// 歌曲详情
Route::get('/music/{music_id}', 'MusicController@getMusicInfo')->where(['music_id' => '[0-9]+']);

Route::group([
	'middleware' => [
		'auth:api',
	]
], function() {
	// 添加歌曲评论
	Route::post('/music/{music_id}/comment', 'MusicCommentController@addComment')
	->middleware(MusicMiddleware\VerifyCommentContent::class); // 验证评论内容
	// 查看歌曲评论列表
	Route::get('/music/{music_id}/comment', 'MusicCommentController@getMusicCommentList');
	// 添加专辑评论
	Route::post('/music/special/{special_id}/comment', 'MusicCommentController@addSpecialComment')
	->middleware(MusicMiddleware\VerifyCommentContent::class); // 验证评论内容
	// 查看专辑评论列表
	Route::get('/music/special/{special_id}/comment', 'MusicCommentController@getSpecialCommentList');
	//删除评论 TODO 根据权限及实际需求增加中间件
	Route::delete('/music/comment/{comment_id}', 'MusicCommentController@delComment');
	// 点赞
	Route::post('/music/{music_id}/digg', 'MusicDiggController@diggMusic');
	// 取消点赞
	Route::delete('/music/{music_id}/digg', 'MusicDiggController@cancelDiggMusic');
	// 收藏
	Route::post('/music/{music_id}/collection', 'MusicCollectionController@addMusicCollection');
	// 取消收藏
	Route::delete('/music/{music_id}/collection', 'MusicCollectionController@delMusicCollection');
});