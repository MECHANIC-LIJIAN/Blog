<?php


namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class PlotCode extends Model
{
    /**
     * 软删除
     */
    use SoftDelete;

}
