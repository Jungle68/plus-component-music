<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicSinger extends Model
{
    protected $table = 'music_singers';

    public function cover()
    {
    	return $this->hasOne(Storage::class, 'id', 'cover')->select('id','image_width','image_height');
    }
}
