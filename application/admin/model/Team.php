<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class Team extends Model
{
    //软删除
    use SoftDelete;
}
