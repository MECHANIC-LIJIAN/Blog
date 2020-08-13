<?php

namespace app\common\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title|文章标题'=>'require',
        'cateid|所属栏目'=>'require',
        'desc|文章摘要'=>'require|max:100',
        'content|文章内容'=>'require',
        'member_id'=>'require'
        
    ];
    protected $message = [
        'desc.max' => '文章摘要的长度不能超过100字，请修改！',
        
    ];

    public function sceneAdd()
    {
        return $this->only(['title','desc','content','cateid','member_id']);
    }

 
    public function sceneEdit()
    {
        return $this->only(['title','desc','content','cateid']);
    }
}
