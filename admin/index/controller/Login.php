<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Login extends Controller{

    public function login(){
        return $this->fetch();
    }

    public function login_decide(){
        $username = input('username');
        $password = $this->encryptDecrypt(input('password'),config('secret_key'));
        $online   = input('online'); 
         
        $m = Db::name('t_admin');

        $res = $m->field('id,username')->where("username='".$username."' and password='".$password."'")->find();
        if($res){
            //记录本次登录时间
            $m = Db::name('t_admin');

            $loginTime = $m->where('id='.$res['id'])->value('login_time');
            session('loginTime',$loginTime);

            $m->where('id='.$res['id'])->update(['login_time' => date('Y-m-d H:i:s')]);

            //设置登录cookie
            if($online){
                cookie('admin',$res['id'],60*60*24*7);
            }else{
                cookie('admin',$res['id'],60*60*6);
            }
            
            //设置session会话
            session('admin',$res);

            $this->success('登录成功！','index/index/index','',1);
        }else{
            $this->error('登录失败！请检查您的账号或密码。','index/login/login','',1);
        }
    }

    public function auth_error(){
        $this->error('没有权限！','index/index/index','',1);
    }

    /**
     * 加密 / 解密
     * @param  string $string 需要操作的字符
     * @param  string $key 秘钥
     * @param  string $decrypt 0为加密，1为解密
     * @return string
     */
    private function encryptDecrypt($string, $key, $decrypt=0){ 
        if($decrypt == 0){ 
            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key)))); 
            return $encrypted; 
        }else{ 
            $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "12"); 
            return $decrypted; 
        } 
    }

}
