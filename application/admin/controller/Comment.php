<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Comment extends Controller
{
    /**
     * 显示评论列表
     *
     * @return \think\Response
     */
    public function list()
    {
        if (request()->isAjax()) {
            $offset=input('post.offset');
            $limit=input('post.limit');
            $page = floor($offset / $limit) + 1;
            $comments=model('Comment')->with('member,article')->order('create_time','asc')->page($page, $limit)->select();
            $total=model('Comment')->count();
            //dump($comments);
            $res=[
                'rows'=>$comments,
                'total'=>$total
            ];
            return json($res);
        }
      
        return view();
    }

    /**
     * 删除评论
     *
     * @return void
     */
    public function del()
    {
        if (request()->isAjax()) {
            $commentInfo=model('Comment')->find(input('post.id'));
           
            $result=$commentInfo->delete();
            if ($result==1) {
                $this->success('评论删除成功', 'admin/comment/list');
            } else {
                $this->error("评论删除失败");
            }
        }
        return view();
    }
}
