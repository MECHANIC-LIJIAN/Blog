<?php

namespace app\common\validate;

use think\Validate;

class PlotCate extends Validate
{
    protected $rule = [
        'catename|栏目名称' => 'require|unique:PlotCate',
        'sort|排序数字' => 'require|number',
    ];

    public function sceneAdd()
    {
        return $this->only(['catename', 'sort']);
    }

    public function sceneSort()
    {
        return $this->only(['sort']);
    }

    public function sceneEdit()
    {
        return $this->only(['catename']);
    }
}
