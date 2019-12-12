<?php

namespace app\blog\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\facade\Session;

class Member extends Model
{
    //软删除
    use SoftDelete;


    // 定义多种登录方式

    private $loginWay = [
        // 用户名
        'username',
        // 邮箱
        'email',
    ];

    /**
     * 会员注册
     *
     * @param [type] $data
     * @return void
     */
    public function register($data)
    {
        $validate = new \app\common\validate\Member();
        if (!($validate->scene('register')->check($data))) {
            return $validate->getError();
        }

        $memebr=new Member;
        $memebr->username=$data['username'];
        $memebr->password=$data['password'];
        $memebr->email=$data['email'];
        $memebr->verify=$data['verify'];
        $memebr->pic="/static/public/imgs/moren.jpg";
        $memebr->save();
        $id=$memebr->id;
        
        if ($id) {
            model('Count')->insert(['member_id'=>$id]);
            $info=new Meminfo;
            $info->member_id=$id;
            $info->username=$data['username'];
            $info->nickname=$data['username'];
            $info->save();
            $keydate = time();
            $data['emailkey'] = md5($data['email']);
            $user->where(array('email' => $data['email']))->setField(array('email_key' => $data['emailkey'], 'datatime' => $keydate));
            // Content
            $emaildate = date('Y-m-d h:i:s', time());
            $content = '<html><head></head><body><div style="font-family:黑体;min-height:300px; background:#57bfaa;min-width:300px;max-width: 1000px;border: 0px solid #ccc; margin: auto;">';
            $content .= '<div style="width: 100%;font-size:20px;text-align: center;background: #4484c5; height: 50px;color: #FFF;line-height: 50px">邮件提醒</div>';
            $content .= '<div style="padding: 20px;color: #fff">';
            $content .= '<h3>尊敬的【' . $data['username'] . '】你好：</h3>';
            $content .= '<p style="line-height: 30px">欢迎您在本网站注册</p>';
            $content .= "注册成功，你在本站注册的邮箱需要验证！请点击<a href='http://localhost/checkid/?emailkey=" . $data['emailkey'] . "&email=" . $data['email'] . "'>http://localhost/checkid/?emailkey=" . $data['emailkey'] . "&email=" . $data['email'] . "</a>(或者复制到浏览器打开)，完成验证！";
            $content .= '<p style="line-height: 30px">此邮件为系统自动发送，请勿直接回复！</p>';
            $content .= '<p style="text-align: right;">XXX团队</p>';
            $content .= '<p style="text-align: right;">' . $emaildate . '</p>';
            $content .= '</div>';
            $content .= '</div></body></html>';
            mailto($data['email'], $content);

            return 1;
        } else {
            return "注册失败！";
        }
    }

    //激活验证
    public function checkid($data)
    {
        $user = new Member();
        $usersta = $user->where(array('email' => $data['email'], 'status' => 0))->select();
        if ($usersta->isEmpty()) {
            return '你的账号已经激活，不需要再次激活!';
        } else {
            $result = $user->where(array('email' => $data['email'], 'email_key' => $data['emailkey']))->select();
            if ($result) {
                $keytime = $result[0]['datatime'];
                $presenttime = time();
                $agotime = ($presenttime - $keytime);
                if ($agotime > 3600) {
                    return "超过10分钟,链接失效";
                } else {
                    $result = $user->where(array('email' => $data['email'], 'email_key' => $data['emailkey']))->setField('status', '1');
                    return 1;
                }
            } else {
                return '激活失败重新激活';
            }
        }
    }

    /**
     * 登录校验
     *
     * @param array $data
     * @return void
     */
    public function login($data)
    {
        $validate = new \app\common\validate\Member();
        if (!$validate->scene('login')->check($data)) {
            return $validate->getError();
        } else {
            if (checkcapcha($data['verify'])==true) {
                // 使用循环方式判断用户名是否存在
                foreach ($this->loginWay as $k=>$v) {
                    $result = $this->where($v, $data['username'])->find();
                    // 如果存在就有这个用户，跳出
                    if ($result) {
                        break;
                    }
                }

                
                //判断是否存在
                if ($result) {
                    //判断用户是否可用
                    if ($result['status'] == 0) {
                        return "用户不可用！";
                    } elseif ($result['password']==$data['password']) {
                        //1 表示用户验证正确
                        $nickname=model('Meminfo')->where('member_id', $result['id'])->field('nickname')->find();
                        $sessionData = [
                        'id' => $result['id'],
                        'username'=>$result['username'],
                        'email'=>$result['email'],
                        'nickname'=>$nickname['nickname']
                    ];
                        session('member', $sessionData);
                        session('session_start_time', time());//记录会话开始时间！判断会话时间的重点！重点！重点！
        
                        // dump($sessionData);
                        return 1;
                    } else {
                        return "密码错误！";
                    }
                } else {
                    return "用户不存在！";
                }
            } else {
                return "验证码错误";
            }
        }
    }

    //重置密码
    public function reset($data)
    {
        $validate = new \app\common\validate\Member();
        if (!$validate->scene('reset')->check($data)) {
            return $validate->getError();
        } else {
            $result = ($data['code'] == session('code'));
            if ($result == false) {
                return "验证码不正确";
            } else {
                $result = $this->where('email', $data['email'])
                ->update([
                    'password'=>$data['password'],
                ]);

                if ($result !==false) {
                    return 1;
                } else {
                    return '重置密码失败';
                }
            }
        }
    }



  

    /**
     * 添加会员
     *
     * @return void
     */
    public function add($data)
    {
        $validate = new \app\common\validate\Member();
        if (!($validate->scene('add')->check($data))) {
            return $validate->getError();
        }
        $result = $this->allowField(true)->save($data);
        if ($result) {
            return 1;
        } else {
            return '会员添加失败';
        }
    }

    /**
     * 用户编辑信息
     */
    public function edit($data)
    {
        $validate = new \app\common\validate\Member();
        if (!($validate->scene('edit')->check($data))) {
            return $validate->getError();
        }
        $memberInfo=$this->where('email', $data['email'])->find();
        $memberInfo->pic=$data['pic'];
        $result =$memberInfo->save();
        if ($result !== false) {
            return 1;
        } else {
            return '更新失败';
        }
    }
}
