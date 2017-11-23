<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Offer extends Common{

    //报价管理
    public function offer_list(){
        $qer        = '1=1';
        $qer_count  = '';
        $m          = Db::name('t_info_offer');

        if(input('info_id')){
            $qer        .= ' and a.info_id='.input('info_id');
            $qer_count  .= 'info_id='.input('info_id');
        }

        $data = $m
        ->field(
            'a.offer_id as offer_id,
            a.original_price as original_price,
            a.number as number,
            a.freight as freight,
            a.tax as tax,
            a.total_price as total_price,
            a.remark as remark,
            a.is_deal as is_deal,
            a.addtime as addtime,
            b.image as image,
            b.model_name as model_name,
            c.account as purchase_account,
            d.account as supply_account'
            )
        ->alias('a')
        ->join('t_info b','a.info_id=b.info_id','LEFT')
        ->join('t_user c','a.purchase_id=c.user_id','LEFT')
        ->join('t_user d','a.supply_id=d.user_id','LEFT')
        ->where($qer)
        ->paginate(20);

        //var_dump($data);die;

        $count_offer = $m->where($qer_count)->count();

        $this->assign('data',$data);
        $this->assign('count_offer',$count_offer);
        parent::_header();
        return $this->fetch();
    }

    public function offer_edit(){
        $order_id = input('order_id');

        $order = Db::name('t_info_offer')->where('order_id',$order_id)->find();

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
            $data['image']    = input('prev_image');
        }


        $res = Db::name('t_user_order')->where("order_id=".$order_id)->update($data);

        if($res){
            $this->success('修改成功！','order_list','',1);
        }else{
            $this->error('修改失败！','order_list','',1);
        }
    }

    public function del(){
        $order_id = input('order_id');

        $m = Db::name('t_user_order');

        $image = $m->where('order_id',$order_id)->value('image');
        if($image != '') {
            $res1 = unlink('./uploads/'.$image);
        }else{
            $res1 = true;
        }

        $res2 = $m->delete($order_id);

        if($res1 && $res2){
            $this->success('删除成功！','order_list','',1);
        }else{
            $this->error('删除失败！','order_list','',1);
        }
    }
    


}
