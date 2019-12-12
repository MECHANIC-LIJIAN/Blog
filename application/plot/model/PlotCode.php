<?php


namespace app\plot\model;

use think\Model;
use think\model\concern\SoftDelete;

class PlotCode extends Model
{
    /**
     * 软删除
     */
    use SoftDelete;


    /**
     * 关联删除
     *
     * @param [type] $data
     * @return void
     */

    public function code()
    {
        return $this->hasMany('Plot', 'cateid', 'id');
    }
}
