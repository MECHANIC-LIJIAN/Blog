<?php

namespace app\team\controller;

class Team extends Base
{


    /*
     *
     * 团队首页信息
     *
     * */
    public function team()
    {
        $teamId = input('id');
 
        $teamInfo = model("Team")
            ->with('members,members.info,follow')
            ->where('team_id', $teamId)
            ->find();
        $follow = model('Follow')->where(['follow_id' => $teamInfo['member_id'], 'member_id' => session('member.id')])->field('follow_id')->limit(1)->find();
        $viewData = [
                'teamInfo' => $teamInfo,
                'follow' => $follow,
                'keyword' => 'all',
            ];
        //        dump($teamInfo);
        //        dump($follow);
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
//        dump($data);
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
                'teamname' => input('post.teamname'),
                'pic' => input('post.icon'),
                'desc' => input('post.desc'),
                'address' => input('post.address'),
                'institution' => input('post.institution'),
            ];
//            dump($data);
            $result = model('Team')->edit($data);
            if ($result == 1) {
                $this->success('团队信息更新成功!', url('blog/Member/person', ['id' => session('member.id')]));
            } else {
                $this->error($result);
            }
        }
        $teamInfo = model('Team')->where('member_id', session('member.id'))->find();
        $viewData = [
            'teamInfo' => $teamInfo,
        ];
        $this->assign($viewData);
        return view();
    }

    public function icon()
    {
        $file = request()->file('img');
        $data = uploadIcon($file);
        return $data;
    }


    public function myteam()
    {
        $teamId = input('id');
        $teamInfo = model("Team")->where('member_id', $teamId);
        if ($teamInfo != null) {
            $teamInfo = model("Team")->where('member_id', $teamId)->find();
            $articleList = model('Article')
                ->where(['cateid' => 1, 'member_id' => $teamId])
                ->with('members')
                ->order('update_time', 'desc')
                ->select();

            $viewData = [
                'teamInfo' => $teamInfo,
                'keyword' => 'all',
                'articleList' => $articleList,
            ];
            $this->assign($viewData);
            return view();
        } else {
            return 404;
        }
    }


    /*
     * 团队用户验证页面
     * */
    public function cert()
    {
        if (request()->isAjax()) {
            $data = [
                'member_id' => session('member.id'),
                'province' => input('post.province'),
                'city' => input('post.province'),
                'institution' => input('post.institution'),
                'type' => input('post.type'),
                'teamname' => input('post.teamname'),
                'major' => input('post.major'),
                'boss' => input('post.boss'),
                'email' => input('post.email'),
                'desc' => input('post.city'),
            ];

            $data['team_id']="team_".random(8);
            $result = model('Team')->add($data);
            if ($result == 1) {
                $this->success('团队认证提交成功!我们的工作人员会在三个工作日内完成审核，请耐心等待。', url('blog/Member/person', ['id' => session('member.id')]));
            } else {
                $this->error($result);
            }
        }


        $info = model('Member')->where('id', session('member.id'))->field('id,is_team,username')->find();
        if ($info['is_team'] == 1) {
            dump(url('blog/Member/person', ['id' => session('member.id')]));
            $this->error("您已认证成功，无需重复认证。", url('blog/Member/person', ['id' => session('member.id')]));
        } else {
            $province = model('City')->where('pid', 1)->select();
//            dump($province);
            $viewData = [
                'info' => $info,
                'province' => $province
            ];
            $this->assign($viewData);
            return view();
        }
    }



    public function city()
    {
        if (request()->isAjax()) {
            $province=input('post.province');
            $city=model('City')->where('pid', $province)->select();
            return json($city);
        }
    }
}
