<?php
namespace app\search\controller;

use think\Db;
use think\Controller;

class Search extends Controller
{
    public function index()
    {
        $keyword = input('keyword');
        // dump($keyword);
        if ($keyword==null) {
            $this->redirect('index/index/index');
        } else {
            $a = Db::name('article')->field('title')->buildSql();
            $p = Db::name('plot')->field('title')->union([$a])->buildSql();
            // 这样可以将两个表当做是一张表进行查询
            $list = Db::table($p . ' a')->where('title', 'like', '%' . $keyword . '%')->select();
            $viewData = [
            'keyword' => $keyword,
            'list' => $list,
            ];
            // dump($list);
            $this->assign($viewData);
            return view();
        }
    }
}
