<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $table = 'musics';

    public function singer()
    {
    	return $this->hasOne(MusicSinger::class, 'id', 'singer');
    }
}
