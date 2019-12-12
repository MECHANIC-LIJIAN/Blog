<?php
namespace app\blog\model;

use think\Model;

class Collect extends Model
{
    public function memberCollect()
    {
        return $this->belongsTo('Article', 'article_id', 'id');
    }
    public function collect($data)
    {
        $collectInfo=$this->where(['article_id'=>$data['article_id'],'member_id'=>$data['member_id']])->find();

        $article=model("Article");

        // $num=$article->where('id', $data['article_id'])->field('collect_num')->find();
        // $num=$num['collect_num'];

        if (empty($collectInfo)) {
            if ($this->allowField(true)->save($data)) {
                //文章表collect_num字段+1
                $article->where('id', $data['article_id'])->setInc('collect_num');
                // $num=$num+1;
                return json(array('code' => 1,'res' => '加', 'msg' =>'成功'));
            }
        } else {
            if ($this->where(['article_id'=>$data['article_id'],'member_id'=>$data['member_id']])->delete()) {
                //文章表collect_num字段-1
                $article->where('id', $data['article_id'])->setDec('collect_num');
                // $num=$num-1;
                return json(array('code' => 2,'res' => '减', 'msg' =>'成功'));
            }
        }
    }
}
