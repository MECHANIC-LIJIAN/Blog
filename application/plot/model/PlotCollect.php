<?php
namespace app\plot\model;

use think\Model;

class PlotCollect extends Model
{
    public function memberCollect()
    {
        return $this->belongsTo('Plot', 'pic_id', 'id');
    }
    public function collect($data)
    {
        $collectInfo=$this->where(['pic_id'=>$data['pic_id'],'member_id'=>$data['member_id']])->find();

        $plot=model("plot");

        // $num=$plot->where('id', $data['pic_id'])->field('collect_num')->find();
        // $num=$num['collect_num'];

        if (empty($collectInfo)) {
            if ($this->allowField(true)->save($data)) {
                //文章表collect_num字段+1
                $plot->where('id', $data['pic_id'])->setInc('collect_num');
                // $num=$num+1;
                return json(array('code' => 1,'res' => '加', 'msg' =>'成功'));
            }
        } else {
            if ($this->where(['pic_id'=>$data['pic_id'],'member_id'=>$data['member_id']])->delete()) {
                //文章表collect_num字段-1
                $plot->where('id', $data['pic_id'])->setDec('collect_num');
                // $num=$num-1;
                return json(array('code' => 2,'res' => '减', 'msg' =>'成功'));
            }
        }
    }
}
