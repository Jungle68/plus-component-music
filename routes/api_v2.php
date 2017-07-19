<?php

Route::get('/music/specials', 'MusicSpecialController@list');

Route::get('/music/specials/{special}', 'MusicSpecialController@show');