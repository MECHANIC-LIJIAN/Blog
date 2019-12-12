<?php
namespace app\blog\controller;

class Article extends Base
{
    /**
     * 文章详情页
     *
     * @return \think\Response
     */
    public function info()
    {
        $artId = input('id');
    
        $articleInfo = model('Article')->with('comments,comments.memberTwo,comments.member,members')->find($artId);
        // dump($articleInfo);
        $comms = count($articleInfo['comments']);

        $zanif = model('Zan')->where(['member_id' => session('member.id'), 'article_id' => $artId])->find();

        $collectif = model('Collect')->where(['member_id' => session('member.id'), 'article_id' => $artId])->find();
        $articleInfo->setInc('hits');
        $viewData = [
            'articleInfo' => $articleInfo,
            'comms' => $comms,
            'zanif' => $zanif,
            'collectif' => $collectif,
        ];
        $this->assign($viewData);
        return view();
    }

    /**
     * 添加文章
     *
     * @return \think\Response
     */
    public function subarticle()
    {
        if (request()->isAjax()) {
            $data = [
                'title' => input('post.title'),
                'tags' => input('post.tags'),
                'cateid' => input('post.cateid'),
                'desc' => input('post.desc'),
                'content' => input('post.content'),
                'pic' => input('post.pic'),
                'status' => input('post.status'),
                'member_id' => session('member.id'),
            ];
            // $data['desc']=mb_substr(characet($data['content']), 0, 30);
            $data['character_num']=strlen(characet(str_replace('<p>', '', $data['content'])));
            // dump($data['status']);
            $result = model('Article')->add($data);
            if ($result == 1) {
                $article_num=model('Meminfo')->where(['member_id'=>$data['member_id']])->setInc('article_num');
                $this->success('文章添加成功', url('blog/Member/person', ['id' => session('member.id')]));
            } else {
                $this->error($result);
            }
        }

        return view();
    }


    /**
     * 编辑文章
     * @return \think\response\View
     */
    public function edit()
    {
        // dump(input('id'));
        $articleInfo = model('Article')->find(input('id'));
        $data = [
            'articleInfo' => $articleInfo,
        ];
        // dump($data);
        $this->assign($data);

        if (request()->isAjax()) {
            $data = [
                'id' => input('post.id'),
                'title' => input('post.title'),
                'tags' => input('post.tags'),
                'cateid' => input('post.cateid'),
                'desc' => input('post.desc'),
                'content' => input('post.contentback'),
                'pic' => input('post.pic'),
                'status' => input('post.status'),
            ];
            // dump($data);
            $data['character_num']=strlen(characet(str_replace('<p>', '', $data['content'])));
            $result = model('Article')->edit($data);
            if ($result == 1) {
                $this->success('文章修改成功', url('blog/Member/person', ['id' => session('member.id')]));
            } else {
                $this->error($result);
            }
        }
        return view();
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
            $info = $file->move('../public/uploads/img/article_index'); //在public/创建uploads,获取图片的地址
            if ($info) {
                $url = "/uploads/img/article_index/" . $info->getSaveName(); //在域名加图片的地址进行拼接
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
     * 上传图片
     *
     * @return void
     */
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('../public/uploads/img/article'); //在public/创建uploads,获取图片的地址
        if ($info) {
            $url = "/uploads/img/article/" . $info->getSaveName(); //在域名加图片的地址进行拼接
            $datas = ["errno" => 0, "data" => [$url]];
            return json($datas);
        } else {
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    /**
     * 评论
     *
     * @return void
     */
    public function comm()
    {
        $data = [
            'article_id' => input('post.article_id'),
            'content' => input('post.comment-content'),
            'member_id' => session('member.id'),
            'parent_id' => input('post.parent_id'),
            
        ];
        // dump($data);
        $result = model('Comment')->comm($data);
        if ($result == 1) {
            $this->success('评论成功', url('blog/Article/info', ['id' => $data['article_id']]));
        } else {
            $this->error($result);
        }
    }

    /**
     * 删除评论
     *
     * @return void
     */
    public function delcomm()
    {
        $data = [
            'comment_id' => input('post.comment_id'),
        ];

        $comInfo = model('Comment')->where(['id' => $data['comment_id']])->limit(1)->find();
        $result = $comInfo->delete();

        if ($result) {
            $this->success('删除评论成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * 删除文章
     * @return \think\response\View
     */
    public function del()
    {
        $articleInfo = model('Article')->with('comments')->field('id,member_id')->find(input('post.id'));
        // dump($articleInfo);
        $result = $articleInfo->together('comments')->delete();
        if ($result == 1) {
            $article_num=model('Meminfo')->where(['member_id'=>$articleInfo['member_id']])->setDec('article_num');
            $this->success('文章删除成功', url('blog/Member/person', ['id' => session('member.id')]));
        } else {
            $this->error("文章删除失败");
        }

        return view();
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
                'article_id' => input("post.article_id"),
                'member_id' => session('member.id'),
            ];
            $result = model("Zan")->zan($data);
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
            'article_id' => input("post.article_id"),
            'member_id' => session('member.id'),
        ];
        $result = model("Collect")->collect($data);
        return $result;
    }
}
