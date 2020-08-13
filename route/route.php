<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::rule('/', 'index/Index/index', 'GET');
Route::rule('search', 'search/Search/index', 'GET|POST');
Route::rule('verify', 'blog/GetCaptcha/verify', 'GET|POST');
    
Route::rule('push', 'push/Index/index', 'GET|POST');
Route::rule('pushtest', 'push/Index/test', 'GET|POST');
Route::rule('bind', 'push/Index/bind', 'GET|POST');
Route::rule('sendmsg', 'push/Index/sendMessage', 'POST|GET');

//person
Route::group(
    'person',
    function () {
        Route::rule('register', 'blog/Member/register', 'GET|POST');
        Route::rule('login', 'blog/Member/login', 'GET|POST');
        Route::rule('logout', 'blog/Member/logout', 'GET|POST');
        Route::rule('checkid/', 'blog/Member/checkid/', 'GET|POST');
        Route::rule('forget', 'blog/Member/forget', 'GET|POST');
        Route::rule('reset', 'blog/Member/reset', 'POST');
        Route::rule('[:id]', 'blog/Member/person', 'GET|POST');
        Route::rule('[:id]/card', 'blog/Member/card', 'POST|GET');
        Route::rule('[:id]/articleList', 'blog/Member/articleList', 'POST|GET');
        Route::rule('[:id]/picList', 'blog/Member/picList', 'POST|GET');
        Route::rule('[:id]/collectList', 'blog/Member/collectList', 'POST|GET');
        Route::rule('[:id]/followList', 'blog/Member/followList', 'POST|GET');
        
        Route::rule('m/follow', 'blog/Member/follow', 'POST');
        Route::rule('m/edit', 'blog/Member/edit', 'GET|POST');
        Route::rule('m/icon', 'blog/Member/icon', 'GET|POST');
        Route::rule('m/city', 'blog/Member/city', 'POST');
    }
);
//blog
Route::group(
    'boke',
    function () {
        Route::rule('/', 'blog/Index/index', 'GET');
        Route::rule('cate/<catename>', 'blog/Index/cate', 'GET');
  
        Route::rule('artlist', 'blog/Index/list', 'GET|POST');

        Route::rule('p/detail/<id>', 'blog/Article/info', 'GET');
        Route::rule('p/subarticle', 'blog/Article/subarticle', 'GET|POST');
        Route::rule('p/editarticle/[:id]', 'blog/Article/edit', 'GET|POST');
        Route::rule('p/delarticle', 'blog/Article/del', 'POST');
        Route::rule('p/upload', 'blog/Article/upload', 'POST');
        Route::rule('p/uploadIndex', 'blog/Article/uploadIndex', 'POST');
        Route::rule('p/comm', 'blog/Article/comm', 'POST');
        Route::rule('p/delcomm', 'blog/Article/delcomm', 'POST');
        Route::rule('p/zan', 'blog/Article/zan', 'POST');
        Route::rule('p/collect', 'blog/Article/collect', 'POST');
    }
);

//plot
Route::group(
    'plot',
    function () {
        Route::rule('/', 'plot/Index/index', 'GET|POST');
        Route::rule('cate/[:catename]/[:codeType]', 'plot/Index/cate', 'GET');
        Route::rule('piclist', 'plot/Index/list', 'GET|POST');
       
        Route::rule('detail/[:id]', 'plot/Plot/read', 'GET|POST');
        Route::rule('create', 'plot/Plot/create', 'GET|POST');
        Route::rule('run', 'plot/Plot/runCode', 'POST');
        Route::rule('edit', 'plot/Plot/edit', 'GET|POST');
        Route::rule('add', 'plot/Plot/add', 'GET|POST');
        Route::rule('uploadIndex', 'plot/Plot/uploadIndex', 'POST');
        Route::rule('plot/zan', 'plot/Plot/zan', 'POST');
        Route::rule('plot/collect', 'plot/Plot/collect', 'POST');
    
        Route::rule('str', 'plot/Plot/uniqueStr', 'GET|POST');
    }
);


//Team
Route::group(
    'team',
    function () {
        Route::rule('team/[:id]', 'team/Team/team', 'GET|POST');
        Route::rule('/', 'team/Index/index', 'GET');
        Route::rule('list', 'team/Index/list', 'POST');

        Route::rule('team/[:id]', 'team/Team/team', 'GET|POST');
        Route::rule('event/[:id]', 'team/Index/event', 'GET|POST');
        Route::rule('contact', 'team/team/contact', 'POST');
        Route::rule('follow', 'team/Team/follow', 'GET|POST');
        Route::rule('edit', 'team/Team/edit', 'GET|POST');
        Route::rule('icon', 'team/Team/icon', 'POST');

        Route::rule('cert', 'team/Team/cert', 'GET|POST');
        Route::rule('city', 'team/Team/city', 'POST');
    }
)->allowCrossDomain();

//后台路由
Route::group(
    'admin',
    function () {
        Route::rule('/', 'admin/Index/login', 'GET|POST');
        Route::rule('register', 'admin/Index/register', 'GET|POST');
        Route::rule('checkid/', 'admin/Index/checkid/', 'GET|POST');
        Route::rule('forget', 'admin/Index/forget', 'GET|POST');
        Route::rule('reset', 'admin/Index/reset', 'POST');

        Route::rule('home', 'admin/Home/index', 'GET|POST');
        Route::rule('loginout', 'admin/Home/loginout', 'POST');
        
        #文章类别
        Route::group('article', function () {
            Route::rule('cateList', 'admin/Cate/list', 'GET');
            Route::rule('cateAdd', 'admin/Cate/add', 'GET|POST');
            Route::rule('cateSort', 'admin/Cate/sort', 'GET|POST');
            Route::rule('cateEidt/[:id]', 'admin/Cate/edit', 'GET|POST');
            Route::rule('cateDel/[:id]', 'admin/Cate/del', 'POST');
        });

        #绘图类别
        Route::group('plot', function () {
            Route::rule('cateList', 'admin/PlotCate/list', 'GET');
            Route::rule('cateAdd', 'admin/PlotCate/add', 'GET|POST');
            Route::rule('cateSort', 'admin/PlotCate/sort', 'GET|POST');
            Route::rule('cateEidt/[:id]', 'admin/PlotCate/edit', 'GET|POST');
            Route::rule('cateDel/[:id]', 'admin/PlotCate/del', 'POST');
        });
        
        #文章
        Route::rule('articleList', 'admin/Article/list', 'GET|POST');
        Route::rule('articleAdd', 'admin/Article/add', 'GET|POST');
        Route::rule('articleEdit/[:id]', 'admin/Article/edit', 'GET|POST');
        Route::rule('articleDel/[:id]', 'admin/Article/del', 'POST');
        Route::rule('articleTop', 'admin/Article/top', 'POST');
        Route::rule('upload', 'admin/Article/upload', 'GET|POST');

        Route::rule('public/uploads', 'public/uploads', 'GET|POST');
        #用户
        Route::rule('memberList', 'admin/Member/list', 'GET|POST');
        Route::rule('memberEdit', 'admin/Member/edit', 'GET|POST');
        Route::rule('memberAdd', 'admin/Member/add', 'GET|POST');
        Route::rule('memberDel', 'admin/Member/del', 'GET|POST');
        #管理员
        Route::rule('adminList', 'admin/Admin/list', 'GET');
        Route::rule('adminAdd', 'admin/Admin/add', 'GET|POST');
        Route::rule('adminStatus', 'admin/Admin/status', 'POST');
        Route::rule('adminEdit/[:id]', 'admin/Admin/edit', 'GET|POST');
        Route::rule('adminDel', 'admin/Admin/del', 'GET|POST');
        #评论
        Route::rule('commentList', 'admin/comment/list', 'GET|POST');
        Route::rule('commentDel', 'admin/comment/del', 'GET|POST');
        #团队
        Route::rule('teamList', 'admin/Team/list', 'GET|POST');
        Route::rule('disable', 'admin/Team/disable', 'POST');
        Route::rule('enable', 'admin/Team/enable', 'POST');
        Route::rule('teamDel', 'admin/Team/del', 'POST');
        Route::rule('teamCert', 'admin/Team/cert', 'GET|POST');
        Route::rule('refuse', 'admin/Team/refuse', 'POST');
        Route::rule('pass', 'admin/Team/pass', 'POST');


        Route::rule('system', 'admin/system/index', 'GET|POST');
    }
);
