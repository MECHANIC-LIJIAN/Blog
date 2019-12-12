<?php


namespace app\team\model;


use think\Model;

class Member extends Model
{


    /**
     * 关联用户信息
     *
     * @return void
     */
    public function info()
    {
        return $this->hasOne('\app\blog\model\Meminfo', 'member_id', 'id')->field('member_id,username,pic');
    }






    /**
     * 用户编辑信息
     */
    public function edit($data)
    {
        $validate = new \app\common\validate\Member();
        if (!($validate->scene('edit')->check($data))) {
            return $validate->getError();
        }
        $memberInfo=$this->where('email', $data['email'])->find();
        $memberInfo->pic=$data['pic'];
        $result =$memberInfo->save();
        if ($result !== false) {
            return 1;
        } else {
            return '更新失败';
        }
    }
}