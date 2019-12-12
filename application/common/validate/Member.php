<?php

namespace app\common\validate;

use think\Validate;

class Member extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|unique:member',
        'email|邮箱' => 'require|email|unique:member',
        'password|密码' => 'require',
        'conpass|确认密码' => 'require|confirm:password',
        'verify|验证码' => 'require',

    ];
    protected $message = [
        'email.unique' => '该邮箱已被注册，请更换！',
        'verify.require' => '验证码不能为空',
        'conpass.confirm:password' => '两次输入的密码不一致',
    ];


    //注册场景验证
    public function secneRegister()
    {
        return $this->only(['username', 'password', 'conpass', 'email','verify']);
    }

    //登录场景验证
    public function sceneLogin()
    {
        return $this->only(['username', 'password','verify'])->remove('username', 'unique');
    }

    //添加场景验证
    public function sceneAdd()
    {
        return $this->only(['username', 'password','email']);
    }

    //编辑场景验证
    public function sceneEdit()
    {
        return $this->only(['username'])->remove(['username'=>'unique']);
    }

    //重置密码场景验证
    public function sceneReset()
    {
        return $this->only(['code', 'password', 'conpass', 'email'])->remove(['email'=>'unique','username'=>'token']);
    }
}
