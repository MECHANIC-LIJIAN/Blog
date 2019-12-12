<?php

namespace app\common\validate;

use think\Validate;

class Plot extends Validate
{
    protected $rule = [
        'title|图片标题'=>'require',
        'cateid|图片所属栏目'=>'require',
        'code_type|图片语言'=>'require',
        'index_pic|图片封面图'=>'require',
        'code|代码'=>'require',
        'member_id'=>'require'
    ];

    
    public function sceneAdd()
    {
        return $this->only(['title','cateid','indexPic','code','codeid','member_id']);
    }

 
    public function sceneEdit()
    {
        return $this->only(['code']);
    }
}
