<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\PaidNode;
use Zhiyi\Plus\Models\FileWith;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
     * 专辑收藏记录.
     *
     * @author bs<414606094@qq.com>
     */
    public function collections()
    {
    	return $this->hasMany(MusicCollection::class, 'special_id', 'id');
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

    /**
     * 验证某个用户是否收藏了某个专辑  
     *
     * @author bs<414606094@qq.com>
     * @param  [int]  $user
     * @return boolean
     */
    public function hasCollected(int $user): bool
    {
        $cacheKey = sprintf('music-special-collected:%s,%s', $this->id, $user);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->collections()->newPivotStatementForId($user)->first() !== null;
        Cache::forever($cacheKey, $status);

        return $status;
    }
}
