<?php


namespace app\plot\model;

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

    /**
    * 添加图片
    *
    * @param [type] $data
    * @return void
    */
    public function add($data)
    {
        $validate=new \app\common\validate\Plot();

        if (!$validate->scene('add')->check($data)) {
            return $validate->getError();
        }
        // dump($data);
        $result=$this->allowField(true)->save($data);
        if ($result) {
            return 1;
        } else {
            return '图片添加失败!';
        }
    }

    /**
    * 更新图片
    *
    * @param [type] $data
    * @return void
    */
    public function edit($data)
    {
        $validate=new \app\common\validate\Plot();

        if (!$validate->scene('edit')->check($data)) {
            return $validate->getError();
        }
        // dump($data);
        $picInfo=model("Plot")->where(['id'=>$data['id'],'member_id'=>$data['member_id']])->field("code")->find();
        $picInfo->code=$data['code'];
        $result=$picInfo->save();
        if ($result) {
            return 1;
        } else {
            return '图片添加失败!';
        }
    }
}
