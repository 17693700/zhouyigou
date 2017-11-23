<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Common
{

    public function index(){
        $loginTime =  session('loginTime');

        $this->assign('loginTime',$loginTime);
        //获取页头所有数据
        parent::_header();
        return $this->fetch();
    }

}
