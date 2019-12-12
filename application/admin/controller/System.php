<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class System extends Controller
{
    /**
     * 显示系统设置
     *
     * @return \think\Response
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = [
                'id' => input('post.id'),
                'webname'=>input('post.webname'),
                'copyright'=>input('post.copyright')
            ];
            
            $result = model('System')->edit($data);
            if ($result == 1) {
                $this->success('网站信息编辑成功!', 'admin/Home/index');
            } else {
                $this->error($result);
            }
        }


        $webInfo=model('System')->find();
        //dump($webInfo);
        $viewData=[
            'webInfo'=>$webInfo
        ];
        $this->assign($viewData);
        return view();
    }

}
