<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\PaidNode;
use Zhiyi\Plus\Models\FileWith;
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
    	return $this->hasOne(FileWith::class, 'id', 'storage')->select('id','size');
    }

    /**
     * 专辑付费节点
     * 
     * @author bs<414606094@qq.com>
     */
    public function paidNode()
    {
        return $this->hasOne(PaidNode::class, 'raw', 'id')->where('channel', 'music');
    }
}
