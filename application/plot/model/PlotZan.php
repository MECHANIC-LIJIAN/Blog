<?php
namespace app\plot\model;

use think\Model;

class PlotZan extends Model
{
    public function zan($data)
    { dump($data);
        $zanInfo=$this->where(['pic_id'=>$data['pic_id'],'member_id'=>$data['member_id']])->find();

        $plot=model("Plot");

        $num=$plot->where('id', $data['pic_id'])->field('zan_num')->find();
        $num=$num['zan_num'];
       
        if (empty($zanInfo)) {
            if ($this->allowField(true)->save($data)) {
                //文章表zan字段+1
                $plot->where('id', $data['pic_id'])->setInc('zan_num');
                $num=$num+1;
                return json(array('code' => 1,'num'=>$num, 'res' => '加', 'msg' =>'成功'));
            }
        } else {
            if ($this->where(['pic_id'=>$data['pic_id'],'member_id'=>$data['member_id']])->delete()) {
                //文章表zan字段-1
                $plot->where('id', $data['pic_id'])->setDec('zan_num');
                $num=$num-1;
                return json(array('code' => 2,'num'=>$num, 'res' => '减', 'msg' =>'成功'));
            }
        }
    }
}
