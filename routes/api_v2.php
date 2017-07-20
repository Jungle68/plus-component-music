<?php

Route::get('/music/specials', 'MusicSpecialController@list');

Route::get('/music/specials/{special}', 'MusicSpecialController@show');

Route::get('/music/{music}', 'MusicController@show')->where(['music' => '[0-9]+']);

Route::middleware('auth:api')->group(function () {
	Route::get('/music/collections', 'MusicCollectionController@list');
});