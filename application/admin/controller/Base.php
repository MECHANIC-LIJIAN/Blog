<?php

namespace app\admin\controller;

use think\Controller;
use auth\Auth;
class Base extends Controller
{
    public function initialize()
    {
        $exception_arth_list = [
            'admin/home/index',
            'admin/index/login',
            'admin/home/loginout',
            'admin/home/home',
            'admin/article/list',
        ];
        //获取到当前访问的页面
        $module = request()->module(); //获取当前访问的模块
        $controller = request()->controller(); //获取当前访问的控制器
        $action = request()->action(); //获取当前访问的方法
        $url = $module . '/' . $controller . '/' . $action; //转成字符串
        // dump(strtolower($url));
        /**
         *验证是否登录
         */
        if (!session('?admin.id')) {
            $this->redirect('admin/Index/login');
        }elseif(session('admin.id')!=11){
            if (!in_array(strtolower($url), $exception_arth_list)) {
                $auth=new Auth();
                if (!$auth->check(strtolower($url), session('admin.id'))) {
                    $this->error('您没有权限访问');
                }
            }
        }
    }
}