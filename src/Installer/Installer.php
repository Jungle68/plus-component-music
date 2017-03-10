<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Installer;

use Closure;
use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\{
    route_path,
    resource_path,
    base_path as component_base_path
};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Installer extends AbstractInstaller
{
    /**
     * Get the component info onject.
     *
     * @return Medz\Component\ZiyiPlus\PlusComponentExample\Installer\Info
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getComponentInfo()
    {
        return new Info();
    }

    /**
     * Get the component route file.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function router()
    {
        return route_path();
    }

    /**
     * Get the component resource dir.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function resource()
    {
        return resource_path();
    }

    /**
     * Do run the cpmponent install.
     *
     * @param Closure $next
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function install(Closure $next)
    {
        if (!Schema::hasTable('musics')) {
            Schema::create('musics', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
                $table->softDeletes();
            });
            include component_base_path('/databases/table_musics_column.php');
        }

        if (!Schema::hasTable('music_specials')) {
            Schema::create('music_specials', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/src/databases/table_music_specials_column.php');
        }

        // if (!Schema::hasTable('music_comments')) {
        //     Schema::create('music_comments', function (Blueprint $table) {
        //         $table->engine = 'InnoDB';
        //         $table->increments('id')->comment('主键');
        //         $table->timestamps();
        //     });
        //     include component_base_path('/src/databases/table_comments_column.php');
        // }

        // if (!Schema::hasTable('music_diggs')) {
        //     Schema::create('music_diggs', function (Blueprint $table) {
        //         $table->engine = 'InnoDB';
        //         $table->increments('id')->comment('主键');
        //         $table->timestamps();
        //     });
        //     include component_base_path('/src/databases/table_diggs_column.php');
        // }
        
        $next();
    }

    /**
     * Do run update the compoent.
     *
     * @param Closure $next
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function update(Closure $next)
    {
        include component_base_path('/databases/table_musics_column.php');
        $next();
    }

    public function uninstall(Closure $next)
    {
        Schema::dropIfExists('musics');
        $next();
    }
}
