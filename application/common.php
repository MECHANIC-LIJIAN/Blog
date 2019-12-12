<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
ini_set("error_reporting","E_ALL & ~E_NOTICE");
// 应用公共文件
/**
 * 发送邮件
 *
 * @param 邮箱 $email
 * @param 内容 $content
 * @return void
 */
function mailto($email, $content)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->CharSet = 'utf-8'; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.163.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'lijianwzx@163.com'; // SMTP username
        $mail->Password = 'Lj18846135429'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
        //Recipients
        $mail->setFrom('lijianwzx@163.com', 'Bio');
        $mail->addAddress($email); // Add a recipient
        $mail->Subject = "Bio系统通知";
        $mail->Body = $content;
        $mail->isHTML(true); // Set email format to HTML

        return $mail->send();
    } catch (Exception $e) {
        exception($mail->ErrorInfo, 1001);
    }
}


//sapn替换
function replace($data)
{
    return str_replace('span', 'a', $data);
}


//字符串转数组
function strToArray($data)
{
    return explode(',', $data);
}


function checkcapcha($verify)
{
    $captcha = new \think\captcha\Captcha();
    if (!$captcha->check($verify)) {
        return false;
    } else {
        return true;
    }
}


//计算时间
function dateline($date)
{
    $n = time();
    $t = $n-$date;
    $m = 86400*30;
    if ($t <= 3600) {
        return ceil($t/60).'分钟前';
    } elseif ($t >3600 && $t<86400) {
        return ceil($t/3600).'小时前';
    } elseif ($t >86400 && $t < $m) {
        return ceil($t/86400).'天前';
    } else {
        return date("y年m月d日", $date);
    }
}


/**
 * 上传头像
 */
    function uploadIcon($file)
    {
        if (exif_imagetype($file) != IMAGETYPE_JPEG||exif_imagetype($file) != IMAGETYPE_PNG) {
            echo $file->getError();
        }
        $info = $file->move('../public/uploads/img/touxiang/');        //在public/创建uploads,获取图片的地址
        if ($info) {
            $url = "/uploads/img/touxiang/" . $info->getSaveName();            //在域名加图片的地址进行拼接
            return json(array('code' => 1,'data' => $url, 'msg' =>'上传成功'));
        } else {
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }




    function characet($data)
    {
        if (!empty($data)) {
            $fileType = mb_detect_encoding($data, array('UTF-8','GBK','LATIN1','BIG5')) ;
            if ($fileType != 'UTF-8') {
                $data = mb_convert_encoding($data, 'utf-8', $fileType);
            }
        }
        return strip_tags($data);
    }



    
    /**
    * unique_ID
    * 生成16位以上唯一ID
    * @param str $prefix 前缀
    * @return str $id
    */
    function uniqueStr($length = 16, $prefix = '')
    {
        $id = $prefix;
        $addLength = $length - 13;
        $id .= uniqid();
        if (function_exists('random_bytes')) {
            $id .= substr(bin2hex(random_bytes(ceil(($addLength) / 2))), 0, $addLength);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $id .= substr(bin2hex(openssl_random_pseudo_bytes(ceil($addLength / 2))), 0, $addLength);
        } else {
            $id .= mt_rand(1*pow(10, ($addLength)), 9*pow(10, ($addLength)));
        }
        return $id;
    }


    function search(){
        
    }