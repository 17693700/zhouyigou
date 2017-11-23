<?php
namespace app\index\controller;

use think\Controller;
use think\Auth;
use think\Db;

class Common extends Controller{

    //当任何函数加载的时候都会调用此函数，类似于构造函数
    public function _initialize(){
        $uid = cookie('admin');
        if(empty($uid)){
            $this->redirect('index/login/login');
        }

        $AUTH = new \think\Auth();

        $request = \think\Request::instance();  

        //var_dump($request->module().'/'.$request->controller().'/'.$request->action(), $uid);die;

        if(!$AUTH->check($request->module().'/'.$request->controller().'/'.$request->action(), $uid)){
            echo "<script>window.parent.location.href='".url('index/login/auth_error')."'</script>";
        }
    }

    public function _header(){
        $admin = Db::name('t_admin')->where("id=".cookie('admin')."")->find();

        $m = Db::name('t_auth_group_access');
        $groupId = $m->where("uid=".cookie('admin')."")->value('group_id');

        $g = Db::name('t_auth_group');
        $adminType = $g->where("id=$groupId")->value('title');

        $this->assign('admin',$admin);
        $this->assign('adminType',$adminType);
        return $this->fetch();
    }

    //退出登录
    public function logout(){  
        // 删除登录cookie
        cookie('admin', null);
        // 删除登录session
        session('admin', null);

        $this->redirect('index/login/login');
    }

    /**
     * 加密 / 解密
     * @param  string $string 需要操作的字符
     * @param  string $key 秘钥
     * @param  string $decrypt 0为加密，1为解密
     * @return string
     */
    public function encryptDecrypt($string, $key, $decrypt=0){ 
        if($decrypt == 0){ 
            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key)))); 
            return $encrypted; 
        }else{ 
            $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "12"); 
            return $decrypted; 
        } 
    }

    /**
     * 生成全文搜索分词
     * @param  string $str 需要生成的字符
     * @return array
     */
    public function create_participle($str,$type){
        $res   = '';
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

        if($type == 'string'){
            foreach($chars as $val){
                $res .= $val.' ';
            }
        }else if($type == 'array'){
            $res = $chars;
        }

        return $res;
    }

    //处理上传的图片
    public function image_add($name){
        $file = request()->file($name);
        if($file != ''){
            $info = $file->move(ROOT_PATH.'public'.DS.'uploads');  

            if($info){
                $res = $info->getSaveName();
            }else{
                $res = $info->getError();
            }
        }else{
            $res = false;
        }

        

        return $res;
    }

    //采购信息品牌
    public function info_brand(){
        return Db::name('t_info_brand')->order('order_id asc')->select();
    }

    //采购信息分类
    public function info_catagory(){
        $new_data = array();

        $data=Db::name('t_info_catalog')->order('order_id asc')->select();

        foreach($data as $key=>$val){
            if($val['level'] == 1){
                $new_data[$key] = $val;
                
                foreach($data as $k=>$v){
                    if($v['parent_id'] == $val['cat_id']){
                        $new_data[$key]['child'][$k] = $v;
                    } 
                } 
            }
        }

        return $new_data;
    }

    /***
      *10、PHP获取客户端真实IP
      *@我们经常要用数据库记录用户的IP，以下代码可以获取客户端真实的IP：
      ***/
    //获取用户真实IP 
    public function getIp(){ 
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
            $ip = getenv("HTTP_CLIENT_IP"); 
        else 
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
                $ip = getenv("HTTP_X_FORWARDED_FOR"); 
            else 
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
                    $ip = getenv("REMOTE_ADDR"); 
                else 
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
                        $ip = $_SERVER['REMOTE_ADDR']; 
                    else 
                        $ip = "unknown"; 
        return $ip; 
    }

    /**
     * 获取客户端IP
     * @return [string] [description]
     */
    public function getClientIp(){
      $ip = NULL;
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
      {
         $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
         $pos = array_search('unknown',$arr);
         if(false !== $pos) unset($arr[$pos]);
         $ip = trim($arr[0]);
      }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) 
      {
         $ip = $_SERVER['HTTP_CLIENT_IP'];
      }elseif (isset($_SERVER['REMOTE_ADDR'])) 
      {
         $ip = $_SERVER['REMOTE_ADDR'];
      }
     // IP地址合法验证
      $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
      return $ip;
    }

    /**
     * 按日期获取统计数据
     * @return string
     */
    public function addtime_data($day){
        $data = '';

        if($day=='today'){
          //查询今日数据
          $today=date('Y-m-d 00:00:00');
          $data['addtime'] = array('egt',$today);
          return $data;
        }else if($day=='yesterday'){
          //查询昨日数据
          $start = date("Y-m-d 00:00:00",strtotime("-1 day"));
          $end=date('Y-m-d 00:00:00');
          $data['addtime'] = array('between',array($start,$end));
          return $data; 
        }else if($day=='week'){
          //查询本周数据
          $start=date('Y-m-d 00:00:00',(time()-((date('w')==0?7:date('w'))-1)*24*3600));
          $end=date('Y-m-d H:i:s');
          $data['addtime'] = array('between',array($start,$end));
          return $data; 
        }else if($day=='month'){
          //查询本月数据
          $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
          $start=date('Y-m-01 00:00:00');
          $end = date("Y-m-d 00:00:00",strtotime("$BeginDate +1 month -1 day"));
          $data['addtime'] = array('between',array($start,$end));
          return $data;
        }else if($day=='quarter'){
          //查询本季度数据
          $month=date('m');
          if($month==1 || $month==2 ||$month==3){ 
            $start=date('Y-01-01 00:00:00'); 
            $end=date("Y-03-31 23:59:59"); 
          }elseif($month==4 || $month==5 ||$month==6){ 
            $start=(date('Y-04-01 00:00:00')); 
            $end=(date("Y-06-30 23:59:59")); 
          }elseif($month==7 || $month==8 ||$month==9){ 
            $start=(date('Y-07-01 00:00:00')); 
            $end=(date("Y-09-30 23:59:59")); 
          }else{ 
            $start=(date('Y-10-01 00:00:00')); 
            $end=(date("Y-12-31 23:59:59")); 
          } 
          $data['addtime'] = array('between',array($start,$end));
          return $data;
        }else if($day=='year'){
          //查询本年度数据
          $year=(date('Y-01-01 00:00:00'));
          $data['addtime'] = array('egt',$year);
          return $data;
        }else{
          return $data;
        }
    }




}