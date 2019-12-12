<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{

      /**
     * 首页
     *
     * @return void
     */
    public function index()
    {
        $this->redirect(url('plot/index/index'));
    }


 
    /**
     * 文章列表
     *
     * @return void
     */
    public function list()
    {
        if (request()->isAjax()) {
            $offset=input('post.pageIndex');
            $cateId=input('post.cateId');
            if ($cateId==0) {
                $list = model('Article')
                ->with('members')
                ->page($offset, 5)
                ->select();
                # 查询相关数据
                $count = model('Article')->count();
            # 查询数据条数
            } else {
                $list = model('Article')
                ->where('cateid', $cateId)
                ->with('members')
                ->page($offset, 5)
                ->select();
                # 查询相关数据
                $count = model('Article')->where('cateid', $cateId)->count();
            }
          
            $res = [
            'total' => $count,
            'rows' => $list
        ];
            return $res;
        }
    }

    // public function cateArtlist()
    // {
    //     if (request()->isAjax()) {
    //         $offset=input('post.pageIndex');
           
    //         $list = model('Article')
    //         ->where('catename', input('post.cateid'))
    //         ->page($offset, 5)
    //         ->select();
    //         # 查询相关数据
    //         $count = model('Article')->count();
    //         # 查询数据条数

    //         $res = [
    //         'total' => $count,
    //         'rows' => $list,
    //     ];
    //         return $res;
    //     }
    // }
}
