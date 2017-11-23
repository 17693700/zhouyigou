<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Common{

    public function index(){

        //上次登录时间
        $loginTime                  = Db::name('t_admin')->where('id',cookie('admin'))->value('login_time');

        //采购信息统计
        $info                       = Db::name('t_info');
        $infoCount                  = $info->count();
        $infoCountToday             = $info->where(parent::addtime_data('today'))->count();
        $infoCountYesterday         = $info->where(parent::addtime_data('yesterday'))->count();
        $infoCountWeek              = $info->where(parent::addtime_data('week'))->count();
        $infoCountMonth             = $info->where(parent::addtime_data('month'))->count();

        //报价信息统计
        $offer                      = Db::name('t_info_offer');
        $offerCount                 = $offer->count();
        $offerCountToday            = $offer->where(parent::addtime_data('today'))->count();
        $offerCountYesterday        = $offer->where(parent::addtime_data('yesterday'))->count();
        $offerCountWeek             = $offer->where(parent::addtime_data('week'))->count();
        $offerCountMonth            = $offer->where(parent::addtime_data('month'))->count();

        //文章统计
        $article                    = Db::name('t_article_thread');
        $articleCount               = $article->count();
        $articleCountToday          = $article->where(parent::addtime_data('today'))->count();
        $articleCountYesterday      = $article->where(parent::addtime_data('yesterday'))->count();
        $articleCountWeek           = $article->where(parent::addtime_data('week'))->count();
        $articleCountMonth          = $article->where(parent::addtime_data('month'))->count();

        //用户统计
        $user                       = Db::name('t_user');
        $userCount                  = $user->count();
        $userCountToday             = $user->where(parent::addtime_data('today'))->count();
        $userCountYesterday         = $user->where(parent::addtime_data('yesterday'))->count();
        $userCountWeek              = $user->where(parent::addtime_data('week'))->count();
        $userCountMonth             = $user->where(parent::addtime_data('month'))->count();

        $this->assign('loginTime',$loginTime);
        //采购信息
        $this->assign('infoCount',$infoCount);
        $this->assign('infoCountToday',$infoCountToday);
        $this->assign('infoCountYesterday',$infoCountYesterday);
        $this->assign('infoCountWeek',$infoCountWeek);
        $this->assign('infoCountMonth',$infoCountMonth);
        //报价信息
        $this->assign('offerCount',$offerCount);
        $this->assign('offerCountToday',$offerCountToday);
        $this->assign('offerCountYesterday',$offerCountYesterday);
        $this->assign('offerCountWeek',$offerCountWeek);
        $this->assign('offerCountMonth',$offerCountMonth);
        //文章
        $this->assign('articleCount',$articleCount);
        $this->assign('articleCountToday',$articleCountToday);
        $this->assign('articleCountYesterday',$articleCountYesterday);
        $this->assign('articleCountWeek',$articleCountWeek);
        $this->assign('articleCountMonth',$articleCountMonth);
        //用户
        $this->assign('userCount',$userCount);
        $this->assign('userCountToday',$userCountToday);
        $this->assign('userCountYesterday',$userCountYesterday);
        $this->assign('userCountWeek',$userCountWeek);
        $this->assign('userCountMonth',$userCountMonth);
        //服务器名称
        $this->assign('serverName',php_uname());
        //服务器IP
        $this->assign('serverIp',$_SERVER['SERVER_ADDR']);
        //服务器域名
        $this->assign('serverDomain',$_SERVER['SERVER_NAME']);
        //服务器操作系统
        $this->assign('serverType',php_uname('s'));
        //服务器端口
        $this->assign('serverPort',$_SERVER["SERVER_PORT"]);
        //服务器的语言种类
        $this->assign('serverLanguage',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        //服务器当前时间
        $this->assign('serverDate',date("Y年n月j日 H:i:s"));
        //服务器剩余空间
        $this->assign('serverFree',round((disk_free_space(".")/(1024*1024)),2).'M');
        //服务器脚本超时时间
        $this->assign('serverTime',ini_get("max_execution_time").'s');
        //本文件所在文件夹
        $this->assign('thisFile',$_SERVER["DOCUMENT_ROOT"]);
        //PHP版本
        $this->assign('phpVersion',PHP_VERSION);
        //Apache版本
        $this->assign('apacheVersion',apache_get_version());
        //获取页头所有数据
        parent::_header();
        return $this->fetch();
    }


}
