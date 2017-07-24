<?php

// 专辑列表
Route::get('/music/specials', 'MusicSpecialController@list');

// 专辑详情
Route::get('/music/specials/{special}', 'MusicSpecialController@show');

// 音乐详情
Route::get('/music/{music}', 'MusicController@show')->where(['music' => '[0-9]+']);


// 我收藏的专辑列表
Route::middleware('auth:api')->group(function () {
	
	// 用户收藏列表
	Route::get('/music/collections', 'MusicCollectionController@list');

	// 添加音乐评论
	Route::post('/music/{music}/comments', 'MusicCommentController@store');

	// 获取音乐评论
	Route::get('/music/{music}/comments', 'MusicCommentController@list');

	// 添加专辑评论
	Route::post('/music/specials/{special}/comments', 'MusicCommentController@specialStore');

	// 获取专辑评论
	Route::get('/music/specials/{special}/comments', 'MusicCommentController@specialList');

	// 删除音乐评论
	Route::delete('/music/{music}/comments/{comment}', 'MusicCommentController@delete');

	// 删除专辑评论
	Route::delete('/music/specials/{special}/comments/{comment}', 'MusicCommentController@specialDelete');	
});