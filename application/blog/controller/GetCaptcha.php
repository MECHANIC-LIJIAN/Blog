<?php
namespace app\blog\controller;

use think\captcha\Captcha;

class GetCaptcha 
{
	public function verify()
    {
        $captcha = new Captcha();
        return $captcha->entry();    
    }
    
}