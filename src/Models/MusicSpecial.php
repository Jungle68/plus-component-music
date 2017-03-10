<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicSpecial extends Model
{
    protected $table = 'music_specials';

    public function musics()
    {
        $table = app(MusicSpecialLink::class)->getTable();

        return $this->belongsToMany(Music::class, $table, 'music_id', 'music_id')
            ->withPivot('music_id', 'id');
    }
}
