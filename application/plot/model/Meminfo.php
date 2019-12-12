<?php

namespace app\plot\model;

use think\Model;

class Meminfo extends model
{

    /**
     * 关联图片
     *
     * @return void
     */
    public function pic()
    {
        return $this->hasMany('Plot', 'member_id', 'member_id');
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
