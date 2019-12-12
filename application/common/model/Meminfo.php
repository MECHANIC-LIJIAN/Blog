<?php

namespace app\common\model;

use think\Model;

class Meminfo extends model
{

    /**
     * 关联文章
     *
     * @return void
     */
    public function articles()
    {
        return $this->hasMany('Article', 'member_id', 'member_id');
    }

    /**
     * 关联评论
     *
     * @return void
     */
    public function comments()
    {
        return $this->hasMany('Comment', 'member_id', 'id');
    }

    /**
     * 关联关注信息
     */
    public function follow()
    {
        return $this->hasOne('\app\common\model\Count', 'member_id', 'member_id');
    }
}
