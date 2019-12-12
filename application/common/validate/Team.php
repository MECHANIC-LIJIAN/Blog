<?php

namespace app\common\validate;

use think\Validate;

class Team extends Validate
{
    protected $rule = [
        'member_id'=>'require|unique:team',
        'province|省份' => 'require',
        'city|城市' => 'require',
        'institution|所属机构' => 'require',
        'type|机构类型' => 'require',
        'teamname|团队名' => 'require|unique:team',
        'major|研究方向' => 'require',
        'boss|负责人' => 'require',
        'email|通讯邮箱' => 'require|email',
        'desc|简介' => 'require',
    ];
    protected $message = [
        'member_id.unique'=>'请勿重复提交',
        'province.require' => '请选择省份',
        'city.require' => '请选择所在城市',
        'type.require' => '请选择机构类型',
    ];


    //添加场景验证
    public function sceneAdd()
    {
        return $this->only(['member_id','province','city','institution','type','teamname', 'major','boss','email','desc']);
    }

    //编辑场景验证
    public function sceneEdit()
    {
        return $this->only(['username'])->remove(['username'=>'unique']);
    }

}
