<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Order extends Common{

    //采购信息管理
    public function order_list(){
        $qer    = '1=1';
        $m      = Db::name('t_user_order');

        if(input('order_code')){
            $order_code = input('order_code');
            $qer .= ' and order_code='.$order_code;
        }

        $data = $m
        ->field(
            'a.order_id as order_id,
            a.order_code as order_code,
            a.buy_number as buy_number,
            a.unit_price as unit_price,
            a.freight as freight,
            a.tax as tax,
            a.total_price as total_price,
            a.pay_type as pay_type,
            a.static as static,
            a.recv_province as recv_province,
            a.recv_city as recv_city,
            a.recv_area as recv_area,
            a.recv_address as recv_address,
            a.recv_name as recv_name,
            a.recv_tel as recv_tel,
            a.addtime as addtime,
            b.image as image,
            b.model_name as model_name,
            c.account as shop_account,
            d.account as user_account'
            )
        ->alias('a')
        ->join('t_info b','a.info_id=b.info_id','LEFT')
        ->join('t_user c','a.shop_id=c.user_id','LEFT')
        ->join('t_user d','a.user_id=d.user_id','LEFT')
        ->where($qer)
        ->paginate(20);

        $count_order = $m->where($qer)->count();

        $this->assign('data',$data);
        $this->assign('count_order',$count_order);
        parent::_header();
        return $this->fetch();
    }

    public function order_status(){
        $order_id = input('order_id');
        $status  = input('status');

        echo Db::name('t_user_order')->where('order_id',$order_id)->update(['is_show'=>$status]);
    }

    public function order_edit(){
        $order_id = input('order_id');

        $order = Db::name('t_user_order')->where('order_id',$order_id)->find();

        $this->assign('order',$order);
        $this->assign('brand',parent::order_brand());
        $this->assign('catalog',parent::order_catagory());
        return $this->fetch();
    }

    public function edit(){
        $order_id                   = input('order_id');

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
            $data['image']    = input('recv_image');
        }

        $res = Db::name('t_user_order')->where("order_id=".$order_id)->update($data);

        if($res){
            $this->success('修改成功！','order_list','',1);
        }else{
            $this->error('修改失败！','order_list','',1);
        }
    }
    


}
