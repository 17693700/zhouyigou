<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Admin extends Common{

    public function admin_list(){
        $query_rows = Db::name('t_admin')
                    ->field('a.id as admin_id,a.username as username,a.login_time as login_time,c.title as title')
                    ->alias('a')
                    ->join('t_auth_group_access b','a.id = b.uid','LEFT')
                    ->join('t_auth_group c','b.group_id = c.id','LEFT')
                    ->select();

        $this->assign('query_rows',$query_rows);
        $this->assign('count_admin',count($query_rows));
        parent::_header();
        return $this->fetch();
    }

    public function admin_add(){
        $group = Db::name('t_auth_group')->where('status=1')->order('id desc')->select();

        $this->assign('group',$group);
        return $this->fetch();
        return $this->fetch();  
    }

    public function add(){
        $data               = array();

        $data['username']   = input('username');
        $data['password']   = parent::encryptDecrypt(input('password'),config('secret_key'));
        $data['login_time'] = date('Y-m-d H:i:s');
        $group_id           = input('group_id');

        if($data['username']!='' && $data['password']!='' && $group_id!=''){
            $m = Db::name('t_admin');

            $name = $m->where('username="'.$data['username'].'"')->find();

            if(!$name){

                $res = $m->insert($data);

                $new_user = $m->where('username="'.$data['username'].'"')->find();

                $g_data = array();
                $g_data['uid'] = $new_user['id'];
                $g_data['group_id'] = $group_id;

                $res2 = Db::name('t_auth_group_access')->insert($g_data);

                if($res && $res2){
                    return true;
                }else{
                    return '添加失败！';
                }

            }else{
                return '很抱歉，该用户名已存在！';
            }
        }else{
            return '请补全信息！';
        }
    }

    public function admin_edit(){
        $id = input('id');

        $group = Db::name('t_auth_group')->where('status=1')->order('id desc')->select();

        $data = Db::name('t_admin')
                ->field('a.id as admin_id,a.username as username,c.id as group_id,c.title as title')
                ->alias('a')
                ->join('t_auth_group_access b','a.id = b.uid','LEFT')
                ->join('t_auth_group c','b.group_id = c.id','LEFT')
                ->where('a.id',$id)
                ->find();

        $this->assign('group',$group);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function edit(){
        $id                  = input('id');

        $data                = array();
        $data['username']    = input('username');
        $data['password']    = parent::encryptDecrypt(input('password'),config('secret_key'));
        $group_id            = input('group_id');

        if($data['password']){
            $res1 = Db::name('t_admin')->where("id=".$id)->update(['username'=>$data['username'],'password'=>$data['password']]);
        }else{
            $res1 = Db::name('t_admin')->where("id=".$id)->update(['username'=>$data['username']]);
        }

        
        $res2 = Db::name('t_auth_group_access')->where("uid=".$id)->update(['group_id'=>$group_id]);

        if($res1 && $res2){
            return true;
        }
    }

    public function del(){
        $id = input('id');

        $res1 = Db::name('t_admin')->delete($id);
        $res2 = Db::name('t_auth_group_access')->where('uid',$id)->delete();

        if($res1 && $res2){
            echo true;
        }else{
            echo false;
        }
    }


    public function admin_role(){
        $m = Db::name('t_auth_group');

        $data = $m->order('id desc')->paginate(20);

        $count_data = $m->count();

        $this->assign('data',$data);
        $this->assign('count_data',$count_data);
        parent::_header();
        return $this->fetch(); 
    }

    public function admin_role_add(){
        $query_rows = Db::name('t_auth_rule')->select();

        $this->assign('query_rows',$query_rows);
        return $this->fetch();  
    }

    public function role_add(){
        $data           = array();
        $data['title']  = input('role_name');
        $data['rules']  = implode(",", $_POST['permission_check']);
        $data['status'] = 1;

        Db::name('t_auth_group')->insert($data);
    }

    public function admin_role_edit(){
        $id         = input('id');

        $data       = Db::name('t_auth_group')->where('id',$id)->find();
        $roles      = explode(",", $data['rules']);

        $query_rows = Db::name('t_auth_rule')->select();
        
        foreach($query_rows as $key=>$val){
            $query_rows[$key]['is_chance'] = false;

            foreach($roles as $v){
                if($val['id'] == $v){
                    $query_rows[$key]['is_chance'] = true;
                }
            }
        }

        $this->assign('query_rows',$query_rows);
        $this->assign('data',$data);
        return $this->fetch();  
    }

    public function role_edit(){
        $id                  = input('id');

        $data                = array();
        $data['title']       = input('title');
        $data['status']      = 1;
        $data['rules']       = implode(",", $_POST['rules']);

        Db::name('t_auth_group')->where("id=".$id)->update($data);
    }

    public function role_del(){
        $id = input('id');

        $res = Db::name('t_auth_group')->delete($id);

        if($res){
            echo true;
        }else{
            echo false;
        }
    }


    public function admin_permission(){
        $m      = Db::name('t_auth_rule');

        $data = $m->order('id desc')->paginate(20);

        $count_per = $m->count();

        $this->assign('data',$data);
        $this->assign('count_per',$count_per);
        parent::_header();
        return $this->fetch();  
    }

    public function admin_permission_add(){
        return $this->fetch();  
    }

    public function permission_add(){
        $data                    = array();
        $data['title']           = input('title');
        $data['name']            = input('name');
        $data['type']            = 1;
        $data['status']          = 1;
        $data['condition']       = '';

        if($data['title'] != '' && $data['name'] != ''){
            Db::name('t_auth_rule')->insert($data);
        }
    }

    public function admin_permission_edit(){
        $id   = input('id');
        $data = Db::name('t_auth_rule')->where('id',$id)->find();

        $this->assign('data',$data);
        return $this->fetch();  
    }

    public function permission_edit(){
        $data               = array();
        $data['id']         = input('id');
        $data['title']      = input('title');
        $data['name']       = input('name');
        $data['type']       = 1;
        $data['status']     = 1;
        $data['condition']  = '';

        if($data['id']!='' && $data['title']!='' && $data['name']!=''){
            Db::name('t_auth_rule')->where('id',$data['id'])->update($data);
        } 
    }

    public function permission_del(){
        $id = input('id');

        $res = Db::name('t_auth_rule')->delete($id);

        if($res){
            echo true;
        }else{
            echo false;
        }
    }

}
