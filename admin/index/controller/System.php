<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class System extends Common{

    public function system_setting(){
        $data = Db::name('t_site_config')->limit(1)->find();

        $this->assign('data',$data);
        parent::_header();
        return $this->fetch();
    }

    public function system_edit(){
        $data                       = array();
        $data['id']                 = input('id');
        $data['sitename']           = input('sitename');
        $data['page_keywords']      = input('page_keywords');
        $data['page_description']   = input('page_description');
        $data['copyright']          = input('copyright');
        $data['record_number']      = input('record_number');
        $data['statistical_code']   = input('statistical_code');

        if($data['sitename']=='' || $data['page_keywords']=='' || $data['page_description']==''){
            $this->error('请补全信息再提交！','system_setting','',1);
        }   

        $res = Db::name('t_site_config')->where('id',$data['id'])->update($data);

        if($res == true){
            $this->success('修改成功','system_setting','',1);
        }else{
            $this->error('修改失败','system_setting','',1);
        }
    }

    public function system_log(){

        parent::_header();
        return $this->fetch();
    }

}
