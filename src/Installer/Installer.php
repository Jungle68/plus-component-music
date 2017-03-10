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
            include component_base_path('/databases/table_music_specials_column.php');
        }

        if (!Schema::hasTable('music_special_links')) {
            Schema::create('music_special_links', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_special_links_column.php');
        }

        if (!Schema::hasTable('music_comments')) {
            Schema::create('music_comments', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_comments_column.php');
        }

        if (!Schema::hasTable('music_diggs')) {
            Schema::create('music_diggs', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_diggs_column.php');
        }

        if (!Schema::hasTable('music_collections')) {
            Schema::create('music_collections', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_collections_column.php');
        }

        if (!Schema::hasTable('music_singers')) {
            Schema::create('music_singers', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_singers_column.php');
        }

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
        include component_base_path('/databases/table_music_specials_column.php');
        include component_base_path('/databases/table_music_special_links_column.php');
        include component_base_path('/databases/table_music_comments_column.php');
        include component_base_path('/databases/table_music_diggs_column.php');
        include component_base_path('/databases/table_music_collections_column.php');
        include component_base_path('/databases/table_music_singers_column.php');
        $next();
    }

    public function uninstall(Closure $next)
    {
        Schema::dropIfExists('musics');
        Schema::dropIfExists('music_comments');
        Schema::dropIfExists('music_specials');
        Schema::dropIfExists('music_special_links');
        Schema::dropIfExists('music_diggs');
        Schema::dropIfExists('music_collections');
        Schema::dropIfExists('music_singers');
        $next();
    }
}
