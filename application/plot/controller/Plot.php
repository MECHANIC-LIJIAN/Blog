<?php

namespace app\plot\controller;

use think\Request;
use think\facade\Cookie;

class Plot extends Base
{

    /**
     * 创建图片.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->runCode();
        return view();
    }


    /**
     * 显示图片
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        $this->runCode();
        $picid = input('id');
    
        $picInfo = model('Plot')->with("members")->field("id,member_id,code,title")->find($picid);
        $zanif = model('PlotZan')->where(['member_id' => session('member.id'), 'pic_id' => $picid])->find();
        $collectif = model('PlotCollect')->where(['member_id' => session('member.id'), 'pic_id' => $picid])->find();
        $picInfo->setInc('hits');
        $viewData = [
            'picInfo' => $picInfo,
            'zanif' => $zanif,
            'collectif' => $collectif,
            'pic_id'=>$picid
        ];
        // dump($picInfo);
        $this->assign($viewData);
        return view();
    }

    /**
     * 运行代码
     *
     * @return void
     */
    public function runCode()
    {
        header("Content-type:text/html;charset=utf-8");
        if (session('member.id')==null) {
            // 判断uuid是否存在
            if (!Cookie::has('path')) {
                $uuid=uniqueStr();
                $path="code/code/".$uuid;
                Cookie::set('path', $path);
            } else {
                $path=Cookie::get('path');
            }
        } else {
            $path="code/code/".session('member.username');
            Cookie::set('path', $path);
        }

        if (!is_dir($path)) {
            //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
            $res=mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            // $order_file="xcopy code/code/* ".$path." 2>&1";
            $order_file="pwd 2>&1";
            $res_file=exec($order_file, $out_file);
            dump($res_file);
        }

        if (request()->isAjax()) {
            $path=Cookie::get('path');
            $code=input('code_text');
            $code=str_replace("&amp;quot;", '"', $code);
            $myfile = fopen($path."/test.txt", "w+") or die("Unable to open code!");
            fwrite($myfile, $code);
            fclose($myfile);

            $order='cd '.$path.'& python excuteIpynb.py -i test.txt -o tt -s Python-3.6.5 2>&1';
            $res=exec($order, $out);
            // dump($res);
           
            $html_file = fopen($path."/tt.html", "r") or die("Unable to open html!");
            $tt= fread($html_file, filesize($path."/tt.html"));
            fclose($html_file);
            
            return $tt;
        }
    }


    /**
     * 增加
     */
    public function add()
    {
        if (request()->isAjax()) {
            $data=[
            'title'=>input('post.title'),
            'cateid'=>input('post.cate'),
            'code_type'=>input('post.code_type'),
            'code'=>input('post.code'),
            'index_pic'=>input('post.pic'),
            'member_id'=>session('member.id')
        ];
            // dump($data);
            $result = model('Plot')->add($data);
            if ($result == 1) {
                $this->success('图片添加成功', url('plot/Index/index'));
            } else {
                $this->error($result);
            }
        }
        return view();
    }


    /**
     * 保存
     */
    public function edit()
    {
        if (request()->isAjax()) {
            $data=[
                'id'=>input('post.id'),
                'code'=>input('post.code'),
                'member_id'=>session('member.id')
            ];
            // dump($data);
            $result = model('Plot')->edit($data);
            if ($result == 1) {
                $this->success('代码更新成功', url('plot/Index/index'));
            } else {
                $this->error($result);
            }
        }
        // return view();
    }
    
    /**
     * 上传封面图片
     *
     * @return void
     */
    public function uploadIndex()
    {
        $file = request()->file('file_data');
        // dump($file);
        if ($file) {
            $info = $file->move('../public/uploads/img/plot_index'); //在public/创建uploads,获取图片的地址
            if ($info) {
                $url = "/uploads/img/plot_index/" . $info->getSaveName(); //在域名加图片的地址进行拼接
                $datas = ["errno" => 0, "data" => [$url]];
                return json($datas);
            } else {
                // 上传失败获取错误信息
                return $file->getError();
            }
        } else {
            // 上传失败获取错误信息
            return $file->getError();
        }
    }


    /**
     * 点赞
     *
     * @return void
     */
    public function zan()
    {
        if (request()->isAjax()) {
            $data = [
                'pic_id' => input("post.pic_id"),
                'member_id' => session('member.id'),
            ];
            $result = model("PlotZan")->zan($data);
            return $result;
        }
    }

    /**
     * 收藏
     *
     * @return void
     */
    public function collect()
    {
        $data = [
            'pic_id' => input("post.pic_id"),
            'member_id' => session('member.id'),
        ];
        $result = model("PlotCollect")->collect($data);
        return $result;
    }
}
