<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class User extends Common{

    public function user_list(){
        $qer    = '1=1';
        $m      = Db::name('t_user');

        if(input('keywords')){
            $keywords = input('keywords');
            $qer .= ' and account like("%'.$keywords.'%")';
        }

        $data = $m->where($qer)->order('user_id desc')->paginate(20);

        $count_user = $m->where($qer)->order('user_id desc')->count();

        $this->assign('data',$data);
        $this->assign('count_user',$count_user);
        parent::_header();
        return $this->fetch();
    }

    public function user_add(){
        return $this->fetch();
    }

    public function add(){
        $data                       = array();
        $data['company_id']         = input('company_id','');
        $data['wechat_id']          = input('wechat_id','');
        $data['account']            = input('account');
        $data['password']           = parent::encryptDecrypt(input('password'),config('secret_key'));
        $data['type']               = input('type');
        $data['mobile']             = input('mobile');
        $data['username']           = input('username');
        $data['sex']                = input('sex');
        $data['head_image']         = parent::image_add('head_image');
        $data['email']              = input('email');
        $data['position']           = input('position');
        $data['tel']                = input('tel');
        $data['qq']                 = input('qq');
        $data['prev_time']          = date('Y-m-d H:i:s');
        $data['addtime']            = date('Y-m-d H:i:s');
        $data['remark']             = input('remark');

        if($data['account'] != '' && $data['password'] != ''){
            $res = Db::name('t_user')->insert($data);

            if($res){
                echo "<script>alert('添加成功！');parent.location.href='user_list'</script>";
            }else{
                echo "<script>alert('添加失败！');parent.location.href='user_list'</script>";
            }
        }else{
            echo "<script>alert('添加失败，带星号的内容不能为空！');parent.location.href='user_list'</script>";
        } 
    }

    public function user_edit(){
        $user_id = input('user_id');

        $user = Db::name('t_user')->where('user_id',$user_id)->find();
        
        $this->assign('user',$user);
        return $this->fetch();
    }

    public function edit(){
        $user_id                    = input('user_id');

        $data                       = array();
        $data['company_id']         = input('company_id','');
        $data['wechat_id']          = input('wechat_id','');
        $data['account']            = input('account');
        $data['password']           = parent::encryptDecrypt(input('password'),config('secret_key'));
        $data['type']               = input('type');
        $data['mobile']             = input('mobile');
        $data['username']           = input('username');
        $data['sex']                = input('sex');
        $data['email']              = input('email');
        $data['position']           = input('position');
        $data['tel']                = input('tel');
        $data['qq']                 = input('qq');
        $data['prev_time']          = input('prev_time');
        $data['addtime']            = input('addtime');
        $data['remark']             = input('remark');

        $head_image                 = parent::image_add('head_image');

        if($head_image != ''){
            $data['head_image']     = $head_image;
        }else{
            $data['head_image']     = input('prev_image');
        }

        if($data['account'] != '' && $data['password'] != ''){
            $res = Db::name('t_user')->where('user_id',$user_id)->update($data);

            if($res){
                echo "<script>alert('修改成功！');parent.location.href='user_list'</script>";
            }else{
                echo "<script>alert('修改失败！');parent.location.href='user_list'</script>";
            }
        }else{
            echo "<script>alert('修改失败，数据缺失！');parent.location.href='user_list'</script>";
        } 
    }

    public function user_change_password(){
        $user_id  = input('user_id');

        $user = Db::name('t_user')->field('user_id,account')->where('user_id',$user_id)->find();

        $this->assign('user',$user);
        return $this->fetch();
    }

    public function change_password(){
        $user_id  = input('user_id');
        $password = parent::encryptDecrypt(input('newpassword'),config('secret_key'));

        Db::name('t_user')->where('user_id',$user_id)->update(['password'=>$password]);
    }

    public function del(){
        $user_id = input('user_id');

        $m = Db::name('t_user');

        $head_image = $m->where('user_id',$user_id)->value('head_image');
        if($head_image != ''){
            $res1 = unlink('./uploads/'.$head_image);
        }else{
            $res1 = true;
        }

        $res2 = $m->delete($user_id);

        if($res1 && $res2){
            $this->success('删除成功！','user_list','',1);
        }else{
            $this->error('删除失败！','user_list','',1);
        }
    }

    public function some_del(){
        $idAll = input('idAll');
        $m     = Db::name('t_user');

        $del_arr = explode(',',$idAll);

        foreach($del_arr as $val){
            if($val != ''){
                $image = $m->where('user_id',$val)->value('head_image');
                if($image != ''){
                    $res1 = unlink('./uploads/'.$image);
                }else{
                    $res1 = true;
                }

                $res2 = $m->where('user_id',$val)->delete();
            }
        }

        if($res1 && $res2){
            $this->success('删除成功！','user_list','',1);
        }else{
            $this->error('删除失败！','user_list','',1);
        }
    }

    public function user_record_browse(){
        parent::_header();
        return $this->fetch();
    }

}
