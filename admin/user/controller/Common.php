<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Cache;

class Common extends Controller{
	/**
    * 页眉 
    */
	  public function head_top(){
        return $this->fetch();
    }

   	
    /**
    * 获取用户资料 
    */
    public function user_info(){
        //$_COOKIE['IYUNTISITE_UUID'] = 84;

        if( !empty($_COOKIE['IYUNTISITE_UUID']) ){
            $sql = "select  * from t_user where user_id=".$_COOKIE['IYUNTISITE_UUID'];
            $user_info = Db::name('t_user')->where('user_id='.$_COOKIE['IYUNTISITE_UUID'])->find();

            //统计待付款，待发货，待收货，待评价的订单数量
            for($i=1;$i<=4;$i++){
            	  $count_order[] = Db::name('t_user_order')->where('user_id='.$_COOKIE['IYUNTISITE_UUID'].' and state='.$i)->count();
            }

            array_push($user_info,$count_order);
        }else{
            $user_info = false;
        }

        return $user_info;
    }
   




}