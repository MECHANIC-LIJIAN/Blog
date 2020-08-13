<?php

namespace app\plot\controller;

use think\Controller;
use think\Request;

class Index extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $picList = model("Plot")
            ->with('code')
            ->field('id,cateid,member_id,title,code_type,index_pic,hits,zan_num,collect_num,update_time')
            ->order('create_time', 'desc')
            ->limit(18)->select();
        // dump($picList[0]);
        $viewData = [
            'picList' => $picList,
            'catename' => 'Plot',
            'keyword' => 'all',
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
        $catename = input('catename');
        $codeType = input('codeType');
        $where = [];
        if ($catename == null & $codeType == null) {
            $where = [
                'status' => 1,
            ];
        } elseif ($catename != null & $codeType == null) {
            $cateId = model("PlotCate")->where('catename', $catename)->field("id")->find();
            $where = [
                'status' => 1,
                'cateid' => $cateId['id'],
            ];
        } elseif ($codeType != null & $catename == null | $catename == "Plot") {
            $codeId = model("PlotCode")->where('code_type', $codeType)->field("id")->find();
            $where = [
                'status' => 1,
                'code_type' => $codeId['id'],
            ];
        } else {
            $cateId = model("PlotCate")->where('catename', $catename)->field("id")->find();
            $codeId = model("PlotCode")->where('code_type', $codeType)->field("id")->find();
            $where = [
                'status' => 1,
                'code_type' => $codeId['id'],
                'cateid' => $cateId['id'],
            ];
        }
        $picList = model('Plot')
            ->where($where)
            ->with('code,members')
            ->field('id,cateid,member_id,title,code_type,index_pic,hits,zan_num,collect_num,update_time')
            ->order('create_time', 'desc')
            ->limit(18)
            ->select();
        // dump($where);
        $viewData = [
            'picList' => $picList,
            'catename' => $catename,
            'codeType' => $codeType,
            'userid' => session('member.id'),
            'cateId' => $cateId['id'],
            'codeId' => $codeId['id'],
        ];

        // $this->assign($viewData);
        return $this->fetch('index', $viewData);
    }

    /**
     * 加载图片
     *
     * @return void
     */
    function list() {
        if (request()->isAjax()) {
            $offset = input('post.pageIndex');
            $cateId = input('post.cateId');
            $keyword = input('post.keyword');
            $codeId = input('post.codeId');
            $where = [];
            if ($keyword == 'all') {
                if ($cateId == null & $codeId == null) {
                    $where = [
                        'status' => 1,
                    ];
                } elseif ($cateId != null & $codeId == null) {
                    $where = [
                        'status' => 1,
                        'cateid' => $cateId,
                    ];
                } elseif ($cateId == null & $codeId != null) {
                    $where = [
                        'status' => 1,
                        'code_type' => $codeId,
                    ];
                } else {
                    $where = [
                        'status' => 1,
                        'cateid' => $cateId,
                        'code_type' => $codeId,
                    ];
                }
            } else {
                $where = ['title', 'like', '%' . $keyword . '%'];
            }
            // dump($where);
            $list = model('Plot')
                ->with('members,code')
                ->where($where)
                ->order('create_time', 'desc')
                ->page($offset, 9)
                ->select();
            $count = count($list);
            // dump($list[0]);
            $res = [
                'total' => $count,
                'rows' => $list,
            ];
            $this->assign($keyword);
            // dump($res);

            return $res;
        }
    }

    public function search()
    {
        $keyword = input('keyword');
        // dump($keyword);
        $picList = model('Plot')
            ->where('title', 'like', '%' . $keyword . '%')
            ->with('members')
            ->order('create_time', 'desc')
            ->limit(12)
            ->select();
        $viewData = [
            'picList' => $picList,
            'catename' => 'Plot',
            'cateId' => 0,
            'keyword' => $keyword,
        ];
        $this->assign($viewData);
        return view('index');
    }
}
