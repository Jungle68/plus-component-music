<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $table = 'musics';

    public function singer()
    {
    	return $this->hasOne(MusicSinger::class, 'id', 'singer');
    }

    public function speciallinks()
    {
    	return $this->hasMany(MusicSpecialLink::class, 'music_id', 'id');
    }

    public function storage()
    {
    	return $this->hasOne(Storage::class, 'id', 'storage')->select('id','image_width','image_height');
    }
}
