<?php
namespace app\user\controller;

class Index extends Common
{   
    /**
    * 用户登录 
    */
    public function user_login()
    {
        return $this->fetch();
    }

    /**
    * 用户注册 
    */
    public function user_register()
    {
        return '用户注册';
    }
}