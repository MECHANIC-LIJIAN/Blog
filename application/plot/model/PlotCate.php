<?php


namespace app\plot\model;

use think\Model;
use think\model\concern\SoftDelete;

class PlotCate extends Model
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

    public function cate()
    {
        return $this->hasMany('Plot', 'cateid', 'id');
    }


}