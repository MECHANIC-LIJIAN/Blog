<?php
namespace app\blog\controller;

class Index extends Base
{

    

    //
    /**
     * 首页
     *
     * @return void
     */
    public function index()
    {
        $articleList = model('Article')
        ->where(['status'=>1])
        ->field('id,cateId,member_id,title,desc,pic,hits,zan_num,collect_num,update_time')
        ->with('members')
        ->order('update_time', 'desc')
        ->limit(15)
        ->select();
        // dump($articleList[0]);

        foreach ($articleList as $up) {
            $up['update_time'] = dateline($up['update_time']);
        }
        $viewData = [
                'articleList' => $articleList,
                'userid' => session('member.id'),
                'keyword'=>'all',
                'cateId'=>0,
                'catename'=>'Blog',
            ];
            
        $this->assign($viewData);
        return view();
    }

    
    /**
         * 分类
         *
         * @return void
         */
    public function cate()
    {
        $catename=input('catename');
        $cateId = model('Cate')->where('catename', $catename)->field('id')->find();
        $articleList = model('Article')
        ->where(['status'=>1,'cateId'=>$cateId['id']])
        ->field('id,cateId,member_id,title,desc,pic,hits,zan_num,collect_num,update_time')
        ->with('members')
        ->order('update_time', 'desc')
        ->limit(15)
        ->select();
        // dump($articleList[0]);

        foreach ($articleList as $up) {
            $up['update_time'] = dateline($up['update_time']);
        }
        $viewData = [
                'articleList' => $articleList,
                'catename'=>$catename,
                'userid' => session('member.id'),
                'cateId' => $cateId['id'],
                'keyword'=>'all'
            ];
            
        // $this->assign($viewData);
        return $this->fetch('index', $viewData);
    }


    /**
     * 文章列表
     *
     * @return void
     */
    public function list()
    {
        if (request()->isAjax()) {
            $offset = input('post.pageIndex');
            $cateId = input('post.cateId');
            $keyword = input('post.keyword');

            if ($keyword == 'all') {
                if ($cateId == 0) {
                    $list = model('Article')
                        ->with('members')
                        ->where(['status'=> 1])
                        ->order('update_time', 'desc')
                        ->page($offset, 5)
                        ->select();
                    # 查询相关数据
                    $count = count($list);
                // dump($list[0]);
                # 查询数据条数
                } else {
                    $list = model('Article')
                        ->where(['cateid'=>$cateId,'status'=>1])
                        ->with('members')
                        ->order('update_time', 'desc')
                        ->page($offset, 5)
                        ->select();
                    # 查询相关数据
                    $count = count($list);
                }
            } else {
                $where=['title', 'like', '%' . $keyword . '%'];
                $list=model('Article')
                ->where('title', 'like', '%' . $keyword . '%')
                ->with('members')
                ->order('create_time', 'desc')
                ->page($offset, 5)
                ->select();
                $count =count($list);
            }
            foreach ($list as $up) {
                $up['update_time'] = dateline($up['update_time']);
            }
            $res = [
                'total' => $count,
                'rows' => $list,
            ];
            $this->assign($keyword);
            // dump($res);
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
