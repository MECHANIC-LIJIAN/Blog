<?php
namespace app\blog\model;

use think\Model;

class Zan extends Model
{
    public function zan($data)
    {
        $zanInfo=$this->where(['article_id'=>$data['article_id'],'member_id'=>$data['member_id']])->find();

        $article=model("Article");

        $num=$article->where('id', $data['article_id'])->field('zan_num')->find();
        $num=$num['zan_num'];

        if (empty($zanInfo)) {
            if ($this->allowField(true)->save($data)) {
                //文章表zan字段+1              
                $article->where('id', $data['article_id'])->setInc('zan_num');
                $num=$num+1;
                return json(array('code' => 1,'num'=>$num, 'res' => '加', 'msg' =>'成功'));
            } 
        } else {
            if ($this->where(['article_id'=>$data['article_id'],'member_id'=>$data['member_id']])->delete()) {
                //文章表zan字段-1
                $article->where('id', $data['article_id'])->setDec('zan_num');
                $num=$num-1;
                return json(array('code' => 2,'num'=>$num, 'res' => '减', 'msg' =>'成功'));
            } 
        }
    }
}
