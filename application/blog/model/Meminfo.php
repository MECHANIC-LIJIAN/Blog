<?php

namespace app\blog\model;

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



    public function edit($data)
    {
        $memberInfo = $this->where('member_id', $data['member_id'])->find();
        // dump($memberInfo);
        if ($memberInfo == null) {
            $result = $this->allowField(true)->save($data);
        } else {
            $province=model("City")->where('id', $data['province'])->field("cityname")->find();
            // dump($province);
            $memberInfo->nickname = $data['nickname'];
            $memberInfo->pic = $data['pic'];
            $memberInfo->sex = $data['sex'];
            $memberInfo->phone = $data['phone'];
            $memberInfo->email = $data['email'];
            $memberInfo->major = $data['major'];
            $memberInfo->desc = $data['desc'];
            $memberInfo->province = $province['cityname'];
            $memberInfo->city = $data['city'];
            $memberInfo->organization = $data['organization'];
            $memberInfo->address = $data['address'];
            $result = $memberInfo->save();
        }

        if (false !== $result || 0 !== $result) {
            return 1;
        } else {
            return '更新失败';
        }
    }
}
