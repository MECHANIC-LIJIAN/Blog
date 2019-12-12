<?php
namespace app\blog\controller;

use think\Controller;
use think\Db;
use think\facade\Session;

class Member extends Base
{

    /**
     * 会员注册
     *
     * @return void
     */
    public function register()
    {
        if (request()->isAjax()) {
            $data = [
                'username' => input('post.username'),
                'password' => md5(input('post.password')),
                'conpass' => md5(input('post.conpass')),
                'email' => input('post.email'),
                'verify' => input('post.verify'),
            ];

            $result = model('Member')->register($data);
            if ($result == 1) {
                $this->success('注册成功，请通过邮件激活账户！', 'blog/Member/login');
            } else {
                $this->error($result);
            }
        }
        return view();
    }

    //激活验证
    public function checkid()
    {
        $data['email'] = request()->param('email');
        $data['emailkey'] = request()->param('emailkey');
        $check = model('Member')->checkid($data);
        if ($check == 1) {
            $this->success('激活成功', 'blog/Member/login');
        } else {
            $this->error($check);
        }
        return view();
    }

    /**
     * 会员登录
     *
     * @return void
     */
    public function login()
    {
        if (session('member')) {
            $this->redirect('index/index/index');
        }

        if (request()->isAjax()) {
            $data = [
                'username' => input('post.username'),
                'password' => md5(input('post.password')),
                'verify' => input('post.verify'),
            ];

            
            $result = model('Member')->login($data);
            if ($result == 1) {
                if (session('redirect_url')!=null) {
                    $this->success('登录成功！', session('redirect_url'));
                    session("redirect_url", null);
                } else {
                    $this->success('登录成功！', 'blog/Index/index');
                }
            } else {
                $this->error($result);
            }
        }

        return view();
    }

    /**
     * 退出登录
     *
     * @return void
     */
    public function logout()
    {
        //销毁session
        session("member", null);
        Session::clear('member');
        //跳转页面
        $this->redirect('index/Index/index');
    }

    //忘记密码,发送验证码
    public function forget()
    {
        if (request()->isAjax()) {
            $email = input('post.email');
            $member = model("Member");
            $memberInfo = $member->where('email', $email)->find();
            if ($memberInfo) {
                $code = mt_rand(1000, 9999);
                session('code', $code);
                $result = mailto($email, $code);
                if ($result) {
                    $this->success("验证码已发送");
                } else {
                    $this->error("验证码发送失败");
                }
            } else {
                $this->error("用户不存在");
            }
        }
        return view();
    }

    //重置密码
    public function reset()
    {
        $data = [
            'code' => input('post.code'),
            'email' => input('post.email'),
            'password' => md5(input('post.password')),
            'conpass' => md5(input('post.conpass')),
        ];

        $result = model('Member')->reset($data);
        if ($result == 1) {
            $this->success('密码重置成功', 'blog/Member/login');
        } else {
            $this->error($result);
        }
        return view();
    }

    /**
     * 个人中心
     */
    public function person()
    {
        $userId = input('id');
        $member = model('Member')->where(['id' => $userId])->field('is_team,email')->limit(1)->find();

        if (!empty($member)) {
            if ($member['is_team'] == 0) {
                $memberInfo = model('Meminfo')->where(['member_id' => $userId])->with('follow')->find();
                $follow = model('Follow')->where(['follow_id' => $userId, 'member_id' => session('member.id')])->field('follow_id')->limit(1)->find();
                
                $viewData = [
                    'memberInfo' => $memberInfo,
                    'follow' => $follow,
                ];
              
                //dump($memberInfo);

//                 dump($follow);
                $this->assign($viewData);
                return $this->fetch();
            } else {
                $team=new \app\team\model\Team();

                $teamId=$team->where(['member_id'=>$userId])->field('team_id')->find();
//                dump($teamId);
                $url=url('team/Team/team', ['id'=>$teamId['team_id']]);
//                dump($url);
                $this->redirect($url);
                // $team=new \app\team\model\Team();
                // $teamInfo=$team->where(['member_id'=>$userId])->limit(1)->find();
                // $articleList = model('Article')
                // ->where(['cateid' =>1, 'member_id' => $userId])
                // ->with('members')
                // ->order('update_time', 'desc')
                // ->select();
                // dump($articleList);
                // $viewData = [
                //     'member'=>$member,
                //     'teamInfo'=>$teamInfo,
                //     'articleList' => $articleList,
                // ];
                // $this->assign($viewData);
                // return $this->fetch("team@Team/my");
            }
        } else {
            return "页面不存在";
        }
    }


    /**
            * 用户名片
            */
    public function card()
    {
        $memberId=input('id');
        $memberInfo = model('Meminfo')->where(['member_id' => $memberId])->with('follow')->find();
        $follow = model('Follow')->where(['follow_id' => $memberId, 'member_id' => session('member.id')])->field('follow_id')->limit(1)->find();
        $viewData = [
                'memberInfo'=>$memberInfo,
                'follow' => $follow,
            ];
            
        $this->assign($viewData);
        return view();
    }


    /**
     * 用户文章
     */
    public function articleList()
    {
        if (request()->isAjax()) {
            $cateId = input('post.cateId');
            $memberId = input('post.memberId');
      
            $where='';
            if ($memberId==session('member.id')) {
                $where=['cateid' => $cateId, 'member_id' => $memberId,];
            } else {
                $where=['cateid' => $cateId, 'member_id' => $memberId,'status'=>1];
            }
            $list = model('Article')
                ->where($where)
                // ->field('id,title,desc,hits,collect_num,zan_num,update_time')
                ->with('members,cate')
                ->order('update_time', 'desc')
                ->select();
            $count =count($list);
            # 查询数据条数
            foreach ($list as $up) {
                $up['update_time'] = dateline($up['update_time']);
            }
            $res = [
                'total' => $count,
                'rows' => $list,
            ];
        
            return $res;
        }


        $memberId=input('id');
        $where='';
        $where='';
        if ($memberId==session('member.id')) {
            $where=['member_id' => $memberId,];
        } else {
            $where=['member_id' => $memberId,'status'=>1];
        }
        $memberInfo = model('Meminfo')->where(['member_id' => $memberId])->with('follow')->find();
        $follow = model('Follow')->where(['follow_id' => $memberId, 'member_id' => session('member.id')])->field('follow_id')->limit(1)->find();
        $articleList = model('Article')
        ->where($where)
        ->field('id,cateId,member_id,title,status,desc,pic,hits,zan_num,collect_num,update_time')
        ->with('members')
        ->order('update_time', 'desc')
        ->limit(15)
        ->select();
        // dump($articleList[0]);

        foreach ($articleList as $up) {
            $up['update_time'] = dateline($up['update_time']);
        }
        $viewData = [
                'articleList' => $articleList,
                'memberInfo'=>$memberInfo,
                'keyword'=>'all',
                'follow' => $follow,
                'catename_curent' => 'all',
            ];
            
        $this->assign($viewData);
        return view();
    }


    /**
     * 用户图片
     */
    public function picList()
    {
        if (request()->isAjax()) {
            $cateId = input('post.cateId');
            $memberId = input('post.memberId');
      
            $where='';
            if ($memberId==session('member.id')) {
                $where=['cateid' => $cateId, 'member_id' => $memberId,];
            } else {
                $where=['cateid' => $cateId, 'member_id' => $memberId,'status'=>1];
            }
            $list = model('Plot')
                ->where($where)
                ->field('id,title,member_id,cateid,code_type,index_pic,hits,collect_num,zan_num,update_time')
                ->with('members,cate,code')
                ->order('update_time', 'desc')
                ->select();
            // dump($list[1]);
            $count =count($list);
            $res = [
                'total' => $count,
                'rows' => $list,
            ];
        
            return $res;
        }


        $memberId=input('id');
        $where=[];
        if ($memberId==session('member.id')) {
            $where=['member_id' => $memberId,];
        } else {
            $where=['member_id' => $memberId,'status'=>1];
        }
        $memberInfo = model('Meminfo')->where(['member_id' => $memberId])->with('follow')->find();
        $picList = model('Plot')
        ->where($where)
        ->field('id,cateid,member_id,title,code_type,status,index_pic,hits,zan_num,collect_num,update_time')
        ->with('members,cate,code')
        ->order('update_time', 'desc')
        ->limit(15)
        ->select();
        // dump($picList[0]);
        $viewData = [
                'picList' => $picList,
                'memberInfo'=>$memberInfo,
            ];
        $this->assign($viewData);
        return view();
    }

    /**
         * 用户关注列表
         */
    public function followList()
    {
        $memberId=input('id');
        $memberInfo = model('Meminfo')->where(['member_id' => $memberId])->with('follow')->find();
        $follow = model('Follow')->where(['follow_id' => $memberId, 'member_id' => session('member.id')])->field('follow_id')->limit(1)->find();
        
        $followlist =  model('Follow')
        ->where(['member_id' => $memberId])
        ->with('memberFollow')
        ->order('update_time', 'desc')
        ->select();
        // dump($followlist);
        foreach ($followlist as $up) {
            $up['update_time'] = dateline($up['update_time']);
        }
        $viewData = [
                'followlist' => $followlist,
                'memberInfo'=>$memberInfo,
                'follow' => $follow,
                // 'keyword'=>'all',
            ];
            
        $this->assign($viewData);
        return view();
    }
    /**
            * 用户收藏列表
            */
    public function collectList()
    {
        $memberId=input('id');
        $memberInfo = model('Meminfo')->where(['member_id' => $memberId])->with('follow')->find();
        $follow = model('Follow')->where(['follow_id' => $memberId, 'member_id' => session('member.id')])->field('follow_id')->limit(1)->find();
        
        $collectlist =  model('Collect')
            ->where(['member_id' => $memberId])
            ->with('memberCollect')
            ->order('update_time', 'desc')
            ->select();
        // dump($collectlist);
        foreach ($collectlist as $up) {
            $up['update_time'] = dateline($up['update_time']);
        }
        $viewData = [
                    'collectlist' => $collectlist,
                    'memberInfo'=>$memberInfo,
                    'follow' => $follow,
                    'keyword'=>'all',
                ];
                
        $this->assign($viewData);
        return view();
    }



    /**
     * 关注
     *
     * @return void
     */
    public function follow()
    {
        $data = [
            'member_id' => session('member.id'),
            'follow_id' => input("post.member_id"),
        ];
        $result = model("Follow")->follow($data);
        return $result;
    }

    /**
     * 修改信息
     */
    public function edit()
    {
        if (request()->isAjax()) {
            $data = [
                'member_id' => session('member.id'),
                'nickname' => input('post.nickname'),
                'sex' => input('post.sex'),
                'pic' => input('post.icon'),
                'phone' => input('post.phone'),
                'email' => input('post.email'),
                'province' => input('post.province'),
                'city' => input('post.city'),
                'major' => input('post.major'),
                'address' => input('post.address'),
                'desc' => input('post.desc'),
                'organization' => input('post.organization'),
            ];
            // return($data);
            $result = model('Meminfo')->edit($data);
            if ($result == 1) {
                $this->success('用户信息更新成功!', url('blog/Member/person', ['id' => session('member.id')]));
            } else {
                $this->error($result);
            }
        }
        $memberInfo = model('Meminfo')->where('member_id', session('member.id'))->find();
        $province = model('City')->where('pid', 1)->select();
        $sql="select cityname from blog_city where pid=(select id from blog_city where cityname='".$memberInfo['province']."')";
        
        $city = Db::table('blog_city')->query($sql);
        $viewData = [
            'memberInfo' => $memberInfo,
            'province'=>$province,
            'city'=>$city
        ];
        $this->assign($viewData);
        return view();
    }
    public function city()
    {
        if (request()->isAjax()) {
            $province=input('post.province');
            // return($province);
            $city=model('City')->where('pid', $province)->select();
            return json($city);
        }
    }
    public function icon()
    {
        $file = request()->file('img');
        $data = uploadIcon($file);
        return $data;
    }
}
