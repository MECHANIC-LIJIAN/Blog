<?php
namespace app\blog\model;

use think\Model;

class Follow extends Model
{
    public function memberFollow()
    {
        return $this->belongsTo('Meminfo', 'follow_id', 'member_id');
    }

    public function follow($data)
    {
        $followInfo=$this->where(['follow_id'=>$data['follow_id'],'member_id'=>$data['member_id']])->limit(1)->find();
        if (empty($followInfo)) {
            if ($this->allowField(true)->save($data)) {
                model('Count')->where(['member_id'=>$data['member_id']])->setInc('follow_num');
                model('Count')->where(['member_id'=>$data['follow_id']])->setInc('followed_num');
                return array('code' => 1,'res' => '关注', 'msg' =>'成功');
            }
        } else {
            if ($this->where(['follow_id'=>$data['follow_id'],'member_id'=>$data['member_id']])->delete()) {
                model('Count')->where(['member_id'=>$data['member_id']])->setDec('follow_num');
                model('Count')->where(['member_id'=>$data['follow_id']])->setDec('followed_num');
                return json(array('code' => 2, 'res' => '取消', 'msg' =>'成功'));
            }
        }
    }
}
