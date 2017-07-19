<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\FileWith;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
	protected $hidden = ['pivot'];

    protected $table = 'musics';

    public function singer()
    {
    	return $this->hasOne(MusicSinger::class, 'id', 'singer');
    }

    public function speciallinks()
    {
    	return $this->hasMany(MusicSpecialLink::class, 'music_id', 'id');
    }

    public function formatStorage(int $user)
    {
    	$storage = FileWith::with('paidNode')->find($this->storage);
    	if (!$storage) {
    		return null;
    	}

    	$file['id'] = $storage->id;
    	if ($storage->paidNode !== null) {
            $file['amount'] = $storage->paidNode->amount;
            $file['type'] = $storage->paidNode->extra;
            $file['paid'] = $storage->paidNode->paid($user);
            $file['paid_node'] = $storage->paidNode->id;
    	}

    	$this->storage = $file;

    	return $this;
    }
}
