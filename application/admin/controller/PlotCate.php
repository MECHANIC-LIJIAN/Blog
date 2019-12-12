<?php

namespace app\admin\controller;

use think\Controller;

class PlotCate extends Base
{
    /**
     * 栏目列表
     *
     * @return void
     */
    public function list()
    {
        $cates = model('PlotCate')
            ->order('sort', 'asc')
            ->paginate(10);
        $viewData = [
            'cates' => $cates,
        ];
        $this->assign($viewData);
        return view();
    }

    /**
     * 增加栏目
     *
     * @return void
     */
    public function add()
    {
        if (request()->isAjax()) {
            $data = [
                'catename' => input('post.catename'),
                'sort' => input('post.sort'),
            ];

            $result = model('PlotCate')->add($data);
            if ($result == 1) {
                $this->success('栏目添加成功', 'admin/PlotCate/list');
            } else {
                $this->error($result);
            }
        }
        return view();
    }

    /**
     * 栏目排序
     */
    public function sort()
    {
        if (request()->isAjax()) {
            $data = [
                'id' => input('post.id'),
                'sort' => input('post.sort'),
                'catename' => input('post.catename'),
            ];
            
            $result = model('PlotCate')->sort($data);
            if ($result == 1) {
                $this->success('排序更新成功!', 'admin/PlotCate/list');
            } else {
                $this->error($result);
            }
        }
    }

    /**
     * 删除栏目
     *
     * @return void
     */
    public function del()
    {
        $cateInfo=model('PlotCate')->with('article,article.comments')->find(input('post.id'));
        foreach ($cateInfo['article'] as $k=>$v) {
            $v->together('comments')->delete();
        }
        $result=$cateInfo->together('article')->delete();
        if ($result == 1) {
            $this->success('栏目删除成功!', 'admin/PlotCate/list');
        } else {
            $this->error('栏目删除失败');
        }
        
        return view();
    }

    /**
     * 修改栏目
     *
     * @return void
     */
    public function edit()
    {
        if (request()->isAjax()) {
            $data = [
                'id' => input('post.id'),
                'catename' => input('post.catename'),
            ];
            
            $result = model('PlotCate')->edit($data);
            if ($result == 1) {
                $this->success('栏目编辑成功!', 'admin/PlotCate/list');
            } else {
                $this->error($result);
            }
        }
        $cateInfo=model('PlotCate')->find(input('id'));
        
        $viewData=[
                    'cateInfo'=>$cateInfo
                    
                ];
        $this->assign($viewData);
        return view();
    }
}
