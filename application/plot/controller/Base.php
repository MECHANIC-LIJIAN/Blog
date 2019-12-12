<?php

namespace app\plot\controller;

use app\common\controller\BaseController;

class Base extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $topPlot = model('Plot')->where('is_top', 1)->order('update_time', 'desc')->limit(10)->select();
        $topTen = model('Plot')->order('hits', 'desc')->limit(10)->select();
        $viewPlot = [
            'topTen'=>$topTen,
            'topPlot'=>$topPlot,
        ];
        // dump($viewPlot);
        $this->assign($viewPlot);
    }
}
