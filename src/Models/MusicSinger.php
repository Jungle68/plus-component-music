<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\Filewith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicSinger extends Model
{
    protected $table = 'music_singers';

    public function cover()
    {
    	return $this->hasOne(Filewith::class, 'id', 'cover')->select('id','size');
    }
}
