<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use DB;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MusicComment extends Model
{
    protected $table = 'music_comments';

    
    /**
     * 覆盖删除方法  同步到tsplus评论表
     * 
     * @author bs<414606094@qq.com>
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public function save(array $options = [])
    {
        $comment = [
            'component' => 'music',
            'user_id' => $this->user_id,
            'to_user_id' => $this->to_user_id,
            'reply_to_user_id' => $this->reply_to_user_id,            
        ];

        DB::transaction(function () use ($comment) {
            parent::save();
            $comment['comment_id'] = $this->id;
            Comment::create($comment);
        });

        return $this;
    }

    /**
     * 同步删除
     * 
     * @author bs<414606094@qq.com>
     * @return [type] [description]
     */
    public function delete()
    {
        $map = [
            'comment_id' => $this->id,
            'component' => 'music',
        ];

        DB::transaction(function () use ($map) {
            parent::delete();
            Comment::where($map)->delete();
        });

        return $this;
    }
}
