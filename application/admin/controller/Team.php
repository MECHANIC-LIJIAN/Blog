<?php

namespace app\admin\controller;

use think\Controller;

class Team extends Base
{
    public function list()
    {
        if (request()->isAjax()) {
            $offset = input('post.offset');
            $limit = input('post.limit');
            $page = floor($offset / $limit) + 1;
            $teams = model('Team')
                ->where('status', '<>', 0)
                ->order(['create_time', 'status' => 'desc'])
                ->page($page, $limit)
                ->select();
            $total=model('Team')->count();
            // $total = count($teams);
            //dump($comments);
            $res = [
                'rows' => $teams,
                'total' => $total
            ];
            return json($res);
        }

        return view();
    }


    public function cert()
    {
        if (request()->isAjax()) {
            $offset = input('post.offset');
            $limit = input('post.limit');
            $page = floor($offset / $limit) + 1;
            $teams = model('Team')->where('status', 0)->order('create_time', 'asc')->page($page, $limit)->select();
            $total = model('Team')->where('status', 0)->count();
//            dump($teams);
            $res = [
                'rows' => $teams,
                'total' => $total
            ];
            return json($res);
        }

        return view();
    }


    public function pass()
    {
        if (request()->isAjax()) {
            $teamId = input('post.id');
//            dump($teamId);
            $teamInfo = model('Team')->where('team_id', $teamId)->field('member_id,teamname,email,status')->find();
            $teamInfo->status=1;
            $res=$teamInfo->save();
//            dump($teamInfo);
            if ($res) {
                $member=new \app\blog\model\Member();
                $memberInfo=$member->where('id', $teamInfo['member_id'])->field('id,is_team')->find();
                $memberInfo->is_team=1;
                $res2=$memberInfo->save();
            }
            if ($res2 !==false) {
                // Content
                $emaildate = date('Y-m-d h:i:s', time());
                $content = '<html><head></head><body><div style="font-family:黑体;min-height:300px; background:#57bfaa;min-width:300px;max-width: 1000px;border: 0px solid #ccc; margin: auto;">';
                $content .= '<div style="width: 100%;font-size:20px;text-align: center;background: #4484c5; height: 50px;color: #FFF;line-height: 50px">邮件提醒</div>';
                $content .= '<div style="padding: 20px;color: #fff">';
                $content .= '<h3>尊敬的【' . $teamInfo['teamname'] . '】你好：</h3>';
                $content .= '<p style="line-height: 30px">很高兴通知您，您在本站申请的团队认证已通过。</p>';
                $content .= '<p style="line-height: 30px">此邮件为系统自动发送，请勿直接回复！</p>';
                $content .= '<p style="text-align: right;">XXX团队</p>';
                $content .= '<p style="text-align: right;">' . $emaildate . '</p>';
                $content .= '</div>';
                $content .= '</div></body></html>';
                mailto($teamInfo['email'], $content);
                $this->success("通过成功！");
            } else {
                $this->error("通过失败！");
            }
        }
    }


    public function refuse()
    {
        if (request()->isAjax()) {
            $teamId = input('post.id');
//            dump($teamId);
            $teamInfo = model('Team')->where('team_id', $teamId)->field('teamname,email')->find();
            $res = $teamInfo->delete();
            if ($res) {
                // Content
                $emaildate = date('Y-m-d h:i:s', time());
                $content = '<html><head></head><body><div style="font-family:黑体;min-height:300px; background:#57bfaa;min-width:300px;max-width: 1000px;border: 0px solid #ccc; margin: auto;">';
                $content .= '<div style="width: 100%;font-size:20px;text-align: center;background: #4484c5; height: 50px;color: #FFF;line-height: 50px">邮件提醒</div>';
                $content .= '<div style="padding: 20px;color: #fff">';
                $content .= '<h3>尊敬的【' . $teamInfo['teamname'] . '】你好：</h3>';
                $content .= '<p style="line-height: 30px">很抱歉通知您，您在本站申请的团队认证已被拒绝。</p>';
                $content .= '<p style="line-height: 30px">此邮件为系统自动发送，请勿直接回复！</p>';
                $content .= '<p style="text-align: right;">XXX团队</p>';
                $content .= '<p style="text-align: right;">' . $emaildate . '</p>';
                $content .= '</div>';
                $content .= '</div></body></html>';
                mailto($teamInfo['email'], $content);
                $this->success("拒绝成功！");
            } else {
                $this->error("拒绝失败！");
            }
        }
    }


    public function disable()
    {
        if (request()->isAjax()) {
            $teamId = input('post.id');
//            dump($teamId);
            $res = model('Team')->where('member_id', $teamId)->update(['status' => 2]);

            if ($res !== false) {
                $this->success("禁用用户成功！");
            } else {
                $this->error("禁用用户失败！");
            }
        }
    }

    public function enable()
    {
        if (request()->isAjax()) {
            $teamId = input('post.id');
//            dump($teamId);
            $res = model('Team')->where('member_id', $teamId)->update(['status' => 1]);
            if ($res !== false) {
                $this->success("启用用户成功！");
            } else {
                $this->error("启用用户失败！");
            }
        }
    }


    public function del()
    {
        if (request()->isAjax()) {
            $teamId = input('post.id');
            $res = model('Team')->where('team_id', $teamId)->find()->delete();
            if ($res !== false) {
                $this->success("删除用户成功！");
            } else {
                $this->error("删除用户失败！");
            }
        }
    }
}
