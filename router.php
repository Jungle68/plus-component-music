<?php

use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;

Route::middleware('web')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\Controllers')
    ->group(component_base_path('/routes/web.php'));

Route::prefix('api/v1')
    ->middleware('api')
    ->namespace('Zhiyi\\Component\\ZhiyiPlus\\PlusComponentMusic\\Controllers')
    ->group(component_base_path('/routes/api.php'));
