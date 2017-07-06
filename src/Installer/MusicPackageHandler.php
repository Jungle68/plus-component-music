<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Installer;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\Permission;
use Zhiyi\Plus\Models\Storage;
use Zhiyi\Plus\Models\File;
use Zhiyi\Plus\Models\FileWith;
use Illuminate\Support\Facades\Schema;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Database\Schema\Blueprint;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicPackageHandler extends PackageHandler
{
    public function defaultHandle($command)
    {
        $handle = $command->choice('Select handle', ['list','install', 'remove', 'checkstorage', 'quit'], 0);

        if ($handle !== 'quit') {
            return $command->call(
                $command->getName(),
                array_merge($command->argument(), ['handle' => $handle])
            );
        }
    }

    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for music')) {
            Comment::where('component', 'music')->delete();
            Permission::whereIn('name', ['music-comment', 'music-digg', 'music-collection'])->delete();
            Schema::dropIfExists('musics');
            Schema::dropIfExists('music_comments');
            Schema::dropIfExists('music_specials');
            Schema::dropIfExists('music_special_links');
            Schema::dropIfExists('music_diggs');
            Schema::dropIfExists('music_collections');
            Schema::dropIfExists('music_singers');

            return $command->info('The Music has been removed');
        }
    }

    public function installHandle($command)
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

        $time = Carbon::now();

        Permission::insert([
            [
                'name' => 'music-comment',
                'display_name' => '评论歌曲',
                'description' => '用户评论歌曲权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'music-digg',
                'display_name' => '点赞歌曲',
                'description' => '用户点赞歌曲权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'music-collection',
                'display_name' => '收藏歌曲',
                'description' => '用户收藏歌曲权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);

        return $command->info('Music component install successfully');
    }

    public function checkstorageHandle($command)
    {
        if ($command->confirm('This will change your datas with new storages')) {
            $musics = Music::get();
            foreach ($musics as $music) {
                $music->storage = $this->checkFileId($music->storage, 'music:storage', $music->id, 1);
                $music->save();

                $singer = $music->singer()->first();
                $singer->cover =  $this->checkFileId($singer->cover, 'music:singer:cover', $singer->id, 1);
                $singer->save();
            } // 迁移音乐相关

            $specials = MusicSpecial::get();
            foreach ($specials as $special) {
                $special->storage = $this->checkFileId($special->storage, 'music:special:storage', $special->id, 1);
                $special->save();
            }
        }

        $command->info('have done');
    }

    protected function checkFileId($storage_id, $channel, $data_id, $user_id = 1)
    {
        $info = Storage::where('id', $storage_id)->first(); // 附件迁移
        $hasMove = FileWith::where('id', $storage_id)->first();  // 已经迁移的不再处理
        if ($info && (!$hasMove)) {
            $file = File::where('hash', $info->hash)->first();
            if (!$file) {
                $file = new File();
                $file->hash = $info->hash;
                $file->origin_filename = $info->origin_filename;
                $file->filename = $info->filename;
                $file->mime = $info->mime;
                $file->width = $info->image_width;
                $file->height = $info->image_height;
                $file->save();
            }

            $filewith = new FileWith();
            $filewith->file_id = $file->id;
            $filewith->user_id = $user_id;
            $filewith->channel = $channel;
            $filewith->raw = $data_id;
            $filewith->size = ($size = sprintf('%sx%s', $file->width, $file->height)) === 'x' ? null : $size;

            return $filewith->id; // 迁移生成成功 返回filewithid
        }

        return $storage_id; // 查找失败暂时原样返回
    }
}
