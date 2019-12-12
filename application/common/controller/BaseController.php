<?php
namespace app\common\controller;

use think\Controller;
use think\facade\Config;

class BaseController extends Controller
{
    public function initialize()
    {
        if (session('member')==null||!session('member')) {

            //不验证用户登陆的页面
            $exception_arth_list = [
                'blog/Index/index',
                'blog/Index/cate',
                'blog/Index/list',
                'blog/Index/search',
                'blog/Index/test',

                'blog/Member/login', //登陆页面
                'blog/Member/register',   //注册页面
                'blog/Member/forget',
                'blog/Member/reset',
                'blog/Member/person',
                'blog/Member/logout',
                'blog/Member/checkid',
                'blog/Member/picList',
                'blog/Member/articleList',
                'blog/Member/collectList',
                'blog/Member/followList',

                'blog/Article/info',

                'plot/Index/index',
                'plot/Index/cate',
                'plot/Index/list',

                'plot/Plot/create',
                'plot/Plot/runCode',
                'plot/Plot/read'
            ];
            //获取到当前访问的页面
            $module = request()->module(); //获取当前访问的模块
            $controller = request()->controller(); //获取当前访问的控制器
            $action = request()->action(); //获取当前访问的方法
            $redirect_url = $module . '/' . $controller . '/' . $action; //转成字符串
            
            if (request()->method()=="GET"&&strtolower($redirect_url)!="blog/member/login") {
                session('redirect_url', request()->url(true));
            }
            //把数组里的全部转小写
            if (!empty($exception_arth_list) && is_array($exception_arth_list)) {
                foreach ($exception_arth_list as &$v) {
                    if (!is_string($v)) {
                        die('不验证页面数组里的值只能为字符串类型.');
                    }
                    $v = strtolower($v);
                }
            }
            // dump($redirect_url);
            // dump($exception_arth_list);
            // dump(in_array(strtolower($redirect_url), $exception_arth_list));
            //当前访问的页面$current_auth_str转为全小写后,如果不在$exception_arth_list客户中就验证用户是否登陆
            if (!empty($exception_arth_list) && is_array($exception_arth_list)) {
                if (!in_array(strtolower($redirect_url), $exception_arth_list)) {
                    $this->redirect("blog/Member/login");
                }
            }
        } elseif (time() - session('session_start_time') >Config::get('session.expire')) {
            session_destroy();//真正的销毁在这里！
            $this->redirect("blog/Member/login");
        }

        //全局显示信息
        $system = new \app\admin\model\System;
        $webInfo = $system->find();
        $articleCates = model('Cate')->order('sort', 'asc')->field("catename,id")->select();
        $picCates=model('PlotCate')->order('sort', 'asc')->field("catename,id")->select();
        $codes=model("PlotCode")->order('sort', 'asc')->field("id,code_type")->select();
        $viewData = [
            'articleCates' => $articleCates,
            'picCates'=>$picCates,
            'webInfo' => $webInfo,
            'codes'=>$codes,
            'keyword'=>'all'
        ];
        $this->view->share($viewData);
    }
}
