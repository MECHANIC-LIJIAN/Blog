<?php
namespace app\admin\controller;

use think\Controller;
use auth\Auth;

class Test extends Controller
{
    public function test()
    {
        $auth=new Auth();
        if ($auth->check('admin/cate/list', session('admin.id'))) {
            echo "Yes,I got it.";
        } else {
            echo "sorry!";
        }
        //参数的验证可以自己构造url，或者使用$_REQUEST['token']='ahai'直接赋值，进行验证；
   // 通过构造url传递参数的话，直接验证即可，
   // 或者自己使用$_REQUEST['token']='ahai'的方式模拟参数，如：
   // $_REQUEST['token']='ahai';
   // $_REQUEST['num']=123;
   // $_REQUEST['abc']=123456;
   // if($auth->check('user/save',1))
   // {
   // echo "Yes,I got it.";
   // }else{
   // echo "sorry!";
   // }
    }
}
