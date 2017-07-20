<?php

// 专辑列表
Route::get('/music/specials', 'MusicSpecialController@list');

// 专辑详情
Route::get('/music/specials/{special}', 'MusicSpecialController@show');


// 音乐详情
Route::get('/music/{music}', 'MusicController@show')->where(['music' => '[0-9]+']);


// 我收藏的专辑列表
Route::middleware('auth:api')->group(function () {
	Route::get('/music/collections', 'MusicCollectionController@list');
});