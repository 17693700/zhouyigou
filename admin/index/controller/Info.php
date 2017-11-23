<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Info extends Common{

    //采购信息管理
    public function info_list(){
        $qer    = '1=1';
        $m      = Db::name('t_info');

        if(input('info_id')){
            $qer .= ' and info_id='.input('info_id');
        }

        $data = $m->where($qer)->order('info_id desc')->paginate(20);

        $count_info = $m->where($qer)->order('info_id desc')->count();

        $this->assign('data',$data);
        $this->assign('count_info',$count_info);
        parent::_header();
        return $this->fetch();
    }

    public function info_add(){
        $this->assign('catagory',parent::info_catagory());
        $this->assign('brand',parent::info_brand());
        return $this->fetch();
    }

    public function add(){
        $data                       = array();
        $data['cat_id']             = input('cat_id');
        $data['brand_id']           = input('brand_id');
        $data['user_type']          = '管理员';
        $data['user_id']            = session('admin')['id'];
        $data['model_name']         = input('model_name');
        $data['participle']         = parent::create_participle(input('model_name'),'string');
        $data['image']              = parent::image_add('image');
        $data['number']             = input('number');
        $data['quality']            = input('quality');
        $data['pack']               = input('pack');
        $data['invoice']            = input('invoice');
        $data['supplier']           = input('supplier');
        $data['term']               = input('term');
        $data['start_time']         = date('Y-m-d H:i:s');
        $data['addtime']            = date('Y-m-d H:i:s');
        $data['remark']             = input('remark');
        $data['end_time']           = input('end_time');
        $data['is_show']            = '1';

        if($data['end_time'] == ''){
            $data['end_time']       = '1900-01-01 00:00:00';  
        }

        if($data['cat_id'] != '' && $data['brand_id'] != '' && $data['model_name'] != '' && $data['number'] != ''){
            $res = Db::name('t_info')->insert($data);

            if($res){
                echo "<script>parent.location.href='info_list'</script>";
            }else{
                echo "<script>alert('添加失败！');parent.location.href='info_list'</script>";
            }
        }else{
            echo "<script>alert('添加失败，带星号的内容不能为空！');parent.location.href='info_list'</script>";
        } 
    }

    public function del(){
        $info_id = input('info_id');

        $m = Db::name('t_info');

        $image = $m->where('info_id',$info_id)->value('image');
        if($image != '') {
            $res1 = unlink('./uploads/'.$image);
        }else{
            $res1 = true;
        }

        $res2 = $m->delete($info_id);

        if($res1 && $res2){
            $this->success('删除成功！','info_list','',1);
        }else{
            $this->error('删除失败！','info_list','',1);
        }
    }

    public function some_del(){
        $idAll = input('idAll');
        $m     = Db::name('t_info');

        $del_arr = explode(',',$idAll);

        foreach($del_arr as $val){
            if($val != ''){
                $image = $m->where('info_id',$val)->value('image');
                if($image != '') {
                    $res1 = unlink('./uploads/'.$image);
                }else{
                    $res1 = true;
                }
                
                $res2 = $m->where('info_id',$val)->delete();
            }
        }

        if($res1 && $res2){
            $this->success('删除成功！','info_list','',1);
        }else{
            $this->error('删除失败！','info_list','',1);
        }
    }

    public function info_status(){
        $info_id = input('info_id');
        $status  = input('status');

        echo Db::name('t_info')->where('info_id',$info_id)->update(['is_show'=>$status]);
    }

    public function info_edit(){
        $info_id = input('info_id');

        $info = Db::name('t_info')->where('info_id',$info_id)->find();

        $this->assign('info',$info);
        $this->assign('brand',parent::info_brand());
        $this->assign('catalog',parent::info_catagory());
        return $this->fetch();
    }

    public function edit(){
        $info_id                    = input('info_id');

        $data                       = array();
        $data['user_type']          = '管理员';
        $data['user_id']            = session('admin')['id'];
        $data['model_name']         = input('model_name');
        $data['participle']         = parent::create_participle(input('model_name'),'string');
        $data['cat_id']             = input('cat_id');
        $data['brand_id']           = input('brand_id');
        $data['number']             = input('number');
        $data['quality']            = input('quality');
        $data['pack']               = input('pack');
        $data['invoice']            = input('invoice');
        $data['supplier']           = input('supplier');
        $data['term']               = input('term');
        $data['start_time']         = date('Y-m-d H:i:s');
        $data['addtime']            = date('Y-m-d H:i:s');
        $data['end_time']           = input('end_time');
        $data['is_show']            = input('is_show');
        $data['remark']             = input('remark');

        $image                      = parent::image_add('image');

        if($image != ''){
            $data['image']    = $image;
        }else{
            $data['image']    = input('prev_image');
        }


        $res = Db::name('t_info')->where("info_id=".$info_id)->update($data);

        if($res){
            $this->success('修改成功！','info_list','',1);
        }else{
            $this->error('修改失败！','info_list','',1);
        }
    }


    //品牌管理
    public function info_brand(){
        $m = Db::name('t_info_brand');
        $data = $m->order('order_id asc')->paginate(20);

        $count_brand = $m->order('order_id asc')->count();

        $this->assign('data',$data);
        $this->assign('count_brand',$count_brand);
        parent::_header();
        return $this->fetch();
    }

    public function info_brand_add(){
        return $this->fetch();
    }

    public function brand_add(){
        $data                       = array();
        $data['brand_name']         = input('brand_name');
        $data['brand_note']         = input('brand_note');
        $data['brand_type']         = input('brand_type');
        $data['order_id']           = input('order_id');
        $data['brand_image']        = parent::image_add('image');
        $data['page_title']         = '';
        $data['page_keywords']      = '';
        $data['page_description']   = '';

        if($data['brand_name'] != '' && $data['brand_type'] != ''){
            $m = Db::name('t_info_brand');
            $res = $m->insert($data);

            if($res){
                echo "<script>alert('添加成功！');parent.location.href='info_brand'</script>";
            }else{
                echo "<script>alert('添加失败！');parent.location.href='info_brand'</script>";
            }
        }else{
            echo "<script>alert('添加失败，带星号的内容不能为空！');parent.location.href='info_brand'</script>";
        } 
    }

    public function info_brand_edit(){
        $brand_id = input('brand_id');

        $brand = Db::name('t_info_brand')->where('brand_id='.$brand_id)->find();

        $this->assign('brand',$brand);
        return $this->fetch();
    }

    public function brand_edit(){
        $brand_id                   = input('brand_id');

        $data                       = array();
        $data['brand_name']         = input('brand_name');
        $data['brand_type']         = input('brand_type');
        $data['brand_note']         = input('brand_note');
        $data['order_id']           = input('order_id');
        $data['page_title']         = '';
        $data['page_keywords']      = '';
        $data['page_description']   = '';

        $brand_image                = parent::image_add('brand_image');

        if($brand_image != ''){
            $data['brand_image']    = $brand_image;
        }else{
            $data['brand_image']    = input('prev_image');
        }

        $res = Db::name('t_info_brand')->where("brand_id=".$brand_id)->update($data);

        if($res){
            $this->success('修改成功！','info_brand','',1);
        }else{
            $this->error('修改失败！','info_brand','',1);
        }
    }

    public function brand_del(){
        $brand_id = input('brand_id');

        $res = Db::name('t_info_brand')->delete($brand_id);

        if($res){
            $this->success('删除成功！','info_brand','',1);
        }else{
            $this->error('删除失败！','info_brand','',1);
        }
    }


    //分类管理
    public function info_catagory(){
        $this->assign('data',parent::info_catagory());
        parent::_header();
        return $this->fetch();
    }

    public function info_catagory_add(){
        $this->assign('data',parent::info_catagory());
        parent::_header();
        return $this->fetch();
    }

    public function catagory_add(){
        $data               = array();
        $data['parent_id']  = input('parent_id',0);
        $data['cat_name']   = input('cat_name');
        $data['order_id']   = input('order_id');

        if($data['cat_name'] != ''){
            $m = Db::name('t_info_catalog');
            $parent_data = $m->where('cat_id='.$data['parent_id'])->select();
            if($parent_data){
                $data['level']      = $parent_data[0]['level'] + 1;
            }else{
                $data['level']      = 1;
            }

            $res = $m->insert($data);
            if($res){
                echo "<script>alert('添加成功！');parent.location.href='info_catagory'</script>";
            }else{
                echo "<script>alert('添加失败！');parent.location.href='info_catagory'</script>";
            }
        }else{
            echo "<script>alert('添加失败，内容不能为空！');parent.location.href='info_catagory'</script>";
        } 
    }

    public function info_catalog_edit(){
        $cat_id = input('cat_id');

        $catalog = Db::name('t_info_catalog')->where('cat_id='.$cat_id)->find();

        $this->assign('catalog',$catalog);
        return $this->fetch();
    }

    public function catalog_edit(){
        $cat_id     = input('cat_id');
        $cat_name   = input('cat_name');
        $order_id   = input('order_id');

        $res = Db::name('t_info_catalog')->where("cat_id=".$cat_id)->update(['cat_name'=>$cat_name,'order_id'=>$order_id]);

        if($res){
            $this->success('修改成功！','info_catagory','',1);
        }else{
            $this->error('修改失败！','info_catagory','',1);
        }
    }

    public function catagory_del(){
        $cat_id = input('cat_id');

        $m = Db::name('t_info_catalog');
        $data = $m->where('parent_id='.$cat_id)->find();

        if($data){
            echo "该分类下面有子分类，不允许删除";
        }else{
            $res = $m->delete($cat_id);
            if($res){
                echo 1;
            }
        }
    }

    


}
