<?php

namespace app\common\validate;

use think\Validate;

class Comment extends Validate
{
protected $rule=[
    'content|评论'=>'require'
];

  //场景验证
  public function sceneComm()
  {
      return $this->only(['content']);
  }
}