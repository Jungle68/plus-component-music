<?php
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Middleware as MusicMiddleware;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\AdminControllers as Controller;

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@show')->name('music:admin');
});
