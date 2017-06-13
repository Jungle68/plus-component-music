<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Installer;

use Zhiyi\Plus\Support\PackageHandler;
use Zhiyi\Plus\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\asset;

class MusicServiceProvider extends ServiceProvider
{
    public function boot()
    {
 	   	$this->loadRoutesFrom(
        	component_base_path('/router.php')
    	); // 路由注入

        $this->publishes([
            component_base_path('resources') => $this->app->PublicPath().'/zhiyicx/plus-component-music'
        ]); // 静态资源

        PackageHandler::loadHandleFrom('music', MusicPackageHandler::class); // 注入安装处理器
    }

    public function register()
    {
        // TODO
    }
}