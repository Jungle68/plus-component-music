<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\Filewith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicSpecial extends Model
{
    protected $table = 'music_specials';

    public function musics()
    {
        return $this->hasMany(MusicSpecialLink::class, 'special_id', 'id');
    }

    public function storage()
    {
    	return $this->hasOne(Filewith::class, 'id', 'storage')->select('id','size');
    }
}
