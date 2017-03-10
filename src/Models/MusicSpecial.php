<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicSpecial extends Model
{
    protected $table = 'music_specials';

    public function musics()
    {
        return $this->hasMany(Music::class, 'id', 'music_id');
    }
}
