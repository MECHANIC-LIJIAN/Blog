<?php

namespace app\admin\model;

use think\Model;

class System extends Model
{
    public function edit($data)
    {
        $validate=new \app\common\validate\System();
        if (!($validate->scene('edit')->check($data))) {
            return $validate->getError();
        }
        
       $result=$this->where('id',$data['id'])
       ->update([
           'webname'=>$data['webname'],
           'copyright'=>$data['copyright']
       ]);
        if ($result!==false) {
            return 1;
        } else {
            return '网站信息更新失败';
        }
    }
    
}
