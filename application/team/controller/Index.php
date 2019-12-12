<?php
namespace app\team\controller;

class Index extends Base
{
    public function index()
    {
        $teamList=model("Team")->limit(15)->select();
        $top=model("Team")->field("pic")->limit(5)->select();
        // dump($teamList);
        $viewData=[
            'teamList'=>$teamList,
            'keyword'=>'all',
            'top'=>$top
        ];
        $this->assign($viewData);
        return view();
    }

    public function list()
    {
        if (request()->isAjax()) {
            $offset = input('post.pageIndex');
            $keyword = input('post.keyword');
            if ($keyword == 'all') {
                    $list = model('Team')
                        ->page($offset, 3)
                        ->select();
                    $count = model('Team')->count();               
            } else {
                $where=['title', 'like', '%' . $keyword . '%'];
                $list=model('Team')
                ->where('major', 'like', '%' . $keyword . '%')
                ->order('create_time', 'desc')
                ->page($offset, 5)
                ->select();
                $count = model('Team')->where('major', 'like', '%' . $keyword . '%')->count();
            }
            foreach ($list as $up) {
                $up['update_time'] = dateline($up['update_time']);
            }
            $res = [
                'total' => $count,
                'rows' => $list,
            ];
            return $res;
        }
    }


}
