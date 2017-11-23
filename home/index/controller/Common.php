<?php
namespace app\index\controller;

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

   	public function head(){
   		if(Cache::get('hot_words') == ''){
   			$hot_words = Db::name('t_hot_words')->order('order_id asc')->select();
   			Cache::set('hot_words',$hot_words);
   		}

   		if(Cache::get('top_catalog') == ''){
   			$top_catalog = $this->all_catalog();
   			Cache::set('top_catalog',$top_catalog);
   		}

   		$this->assign('hot_words',Cache::get('hot_words'));
   		$this->assign('top_catalog',Cache::get('top_catalog'));
   		$this->head_top();

        return $this->fetch();
    }

    /**
    * 页脚 
    */
    public function foot(){
        return $this->fetch();
    }

    /**
    * 全部分类 
    */
    public function all_catalog(){
    	$m = Db::name('t_goods_catalog');
   		$top_catalog = $m->field('cat_id,cat_name')->where('parent_id','0')->order('order_id asc')->select();

   		foreach($top_catalog as $key=>$val){
			   $top_catalog[$key]['medium_catalog'] = $m->field('cat_id,cat_name')->where("parent_id=".$val['cat_id'])->order('order_id asc')->select();
			
      		foreach($top_catalog[$key]['medium_catalog'] as $k=>$v){
      			$top_catalog[$key]['medium_catalog'][$k]['small_catalog'] = $m->field('cat_id,cat_name')->where("parent_id=".$v['cat_id'])->order('order_id asc')->select();
      		}

			    $top_catalog[$key]['brand_classify'] = Db::table('t_brand_classify')->alias('a')->join('t_brand w','a.brand_id = w.brand_id','LEFT')->where('a.cat_id='.$val['cat_id'])->limit('8')->select();
		  }

		return $top_catalog;
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
   
    /**
    * 生成全文搜索字符 
    */
    public function ft_split($str){
        $chars = array(); 

        preg_match_all("/[a-zA-Z&]+/", $str, $out, PREG_SET_ORDER);
        foreach ($out as &$v) {
          $chars[] = $v[0];
        }

        preg_match_all("/[0-9]+/", $str, $out, PREG_SET_ORDER);
        foreach ($out as &$v) {
          $chars[] = $v[0];
        }

        preg_match_all("/[\x{4e00}-\x{9fa5}]/u", $str, $out, PREG_SET_ORDER);
        foreach ($out as &$v) {
          $chars[] = $v[0];
        }

        $chars = array_unique($chars);  

        return $chars;
    }



}
