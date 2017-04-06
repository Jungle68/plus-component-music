<?php
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Middleware as MusicMiddleware;

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function() {
    	return 'music';
    })->name('music:admin');
});