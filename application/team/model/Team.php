<?php


namespace app\team\model;

use think\Model;

class Team extends Model
{
    /**
     * 定义团队与用户关联
     */
    public function members()
    {
        return $this->hasMany('Member', 'team_id', 'team_id')->field('team_id,id');
    }


    /**
     * 关联关注信息
     */
    public function follow()
    {
        return $this->hasOne('\app\common\model\Count', 'member_id', 'member_id')->field('member_id,follow_num,followed_num');
    }


    /**
     *
     * 编辑团队信息
     */

    public function edit($data)
    {
        $teamInfo = $this->where('member_id', $data['member_id'])->find();
        $mInfo=new \app\blog\model\Meminfo();
        $memInfo=$mInfo->where('member_id', $data['member_id'])->field('pic')->find();
        // dump($memInfo);
        if ($teamInfo == null) {
            $result = $this->allowField(true)->save($data);
        } else {
            $memInfo->pic=$data['   pic'];
            $memInfo->save();
            $teamInfo->teamname = $data['teamname'];
            $teamInfo->pic = $data['pic'];
            $teamInfo->desc = $data['desc'];
            $teamInfo->institution = $data['institution'];
            $teamInfo->address = $data['address'];
            $result = $teamInfo->save();
        }

        if (false !== $result || 0 !== $result) {
            return 1;
        } else {
            return '更新失败';
        }
    }



    public function add($data)
    {
        $validate = new \app\common\validate\Team();
        if (!($validate->scene('add')->check($data))) {
            return $validate->getError();
        } else {
//            dump($data);
            if ($this->allowField(true)->save($data)) {
                return 1;
            } else {
                return "提交失败！";
            }
        }
    }
}
