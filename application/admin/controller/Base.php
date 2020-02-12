<?php

namespace app\admin\controller;

use think\Controller;

class Base extends Controller
{
    public function initialize()
    {
        $exception_arth_list = [
            'admin/index/login',
            'admin/home/loginout',
        ];
        //获取到当前访问的页面
        $module = request()->module(); //获取当前访问的模块
        $controller = request()->controller(); //获取当前访问的控制器
        $action = request()->action(); //获取当前访问的方法
        $redirect_url = $module . '/' . $controller . '/' . $action; //转成字符串

        /**
         *验证是否登录
         */
        if (!session("?admin.id")) {
            //当前访问的页面$current_auth_str转为全小写后,如果不在$exception_arth_list客户中就验证用户是否登陆
            if (!empty($exception_arth_list) && is_array($exception_arth_list)) {
                if (!in_array(strtolower($redirect_url), $exception_arth_list)) {
                    $this->redirect("admin/Index/login");
                }
            }
        }

    }
}
