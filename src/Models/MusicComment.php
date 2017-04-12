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
     * 单条评论属于一条音乐
     * @return [type] [description]
     */
    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id', 'id');
    }

    /**
     * 单条评论属于一条专辑
     * @return [type] [description]
     */
    public function special()
    {
        return $this->belongsTo(MusicSpecial::class, 'special_id', 'id');
    }
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
            'comment_table' => 'music_comments',
            'comment_content' => $this->comment_content,
            'user_id' => $this->user_id,
            'to_user_id' => $this->to_user_id,
            'reply_to_user_id' => $this->reply_to_user_id,            
        ];

        if ($this->music_id > 0) {
            $comment['source_table'] = 'musics';
            $comment['source_id'] = $this->music_id;
            $comment['source_cover'] = $this->music->singer->cover;
            $comment['source_content'] = $this->music->title;
        } else {
            $comment['source_table'] = 'music_specials';
            $comment['source_id'] = $this->special_id;
            $comment['source_cover'] = $this->special->storage;
            $comment['source_content'] = $this->special->title;
        }

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
