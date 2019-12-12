<?php


namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Plot extends Model
{
    /**
     * 软删除
     */
    use SoftDelete;

    //关联用户
    public function members()
    {
        return $this->belongsTo('Meminfo', 'member_id', 'member_id')->field('nickname,member_id,pic');
    }
 
 
    //关联类别
    public function cate()
    {
        return $this->belongsTo('PlotCate', 'cateid', 'id')->field('id,catename');
    }

    //关联语言
    public function code()
    {
        return $this->belongsTo('PlotCode', 'code_type', 'id')->field('id,code_type');
    }

}
