<?php

namespace app\blog\model;

use think\Model;
use think\model\concern\SoftDelete;

class Article extends Model
{
    //软删除
    use SoftDelete;

    /**
     * 关联评论
     *
     * @return void
     */
    public function comments()
    {
        return $this->hasMany('Comment', 'article_id', 'id')->field('id,article_id,member_id,parent_id,content,create_time')->order('create_time', 'desc');
    }

    //关联用户
    public function members()
    {
        return $this->belongsTo('Meminfo', 'member_id', 'member_id')->field('nickname,member_id,pic');
    }


    //关联类别
    public function cate()
    {
        return $this->belongsTo('Cate', 'cateid', 'id')->field('id,catename');
    }


    /**
     * 添加文章
     *
     * @param [type] $data
     * @return void
     */
    public function add($data)
    {
        $validate=new \app\common\validate\Article();

        if (!$validate->scene('add')->check($data)) {
            return $validate->getError();
        }
    
        $result=$this->allowField(true)->save($data);
        if ($result) {
            return 1;
        } else {
            return '文章添加失败!';
        }
    }

    /**
     * 编辑文章
     *
     * @param [type] $data
     * @return void
     */
    public function edit($data)
    {
        $validate=new \app\common\validate\Article();

        if (!$validate->scene('edit')->check($data)) {
            return $validate->getError();
        }
        $articleInfo=$this->where('id', $data['id'])->find();
        $articleInfo->title=$data['title'];
        $articleInfo->tags=$data['tags'];
        $articleInfo->pic=$data['pic'];
        $articleInfo->status=$data['status'];
        $articleInfo->cateid=$data['cateid'];
        $articleInfo->content=$data['content'];
        $articleInfo->desc=$data['desc'];

        $result=$articleInfo->save();
        if ($result) {
            return 1;
        } else {
            return '文章修改失败!';
        }
    }
}
