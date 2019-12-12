<?php
namespace app\blog\controller;

use think\Controller;
use app\common\controller\BaseController;

class Base extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        //全局显示信息
        $topArticles = model('Article')->where('is_top', 1)->order('update_time', 'desc')->limit(10)->select();
        $topTen = model('Article')->order('hits', 'desc')->limit(10)->select();
        $viewBlog = [
            'topArticles' => $topArticles,
            'topTen'=>$topTen,
        ];
        // dump($viewBlog);
        $this->view->share($viewBlog);
    }
}
