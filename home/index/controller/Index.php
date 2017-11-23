<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Cache;

class Index extends Common{
    public $sql     = '';
    public $qer     = '';
    public $sqler   = '';
    public $sql_in  = '';
    public $x_qer   = '';
    public $oer     = 'a.`clicks` desc, a.`addtime` desc ';

	/**
    * 首页 
    */
    public function index(){
        //banner
        $this->assign('banner',$this->banner());
    	//用户资料
    	$this->assign('user_info',parent::user_info());
    	//最新采购信息
		$this->assign('purchase_info',$this->purchase_info());
		//最新促销信息
		$this->assign('promotional_information',$this->promotional_information());
		//最新公告
		$this->assign('new_notice',$this->new_notice());
        //楼层信息
        $this->assign('goods_floor',$this->goods_floor());
        //新闻信息
        $this->assign('news_online',$this->news_online());
        //行业资讯
        $this->assign('industry_information',$this->industry_information());
        //轴承知识
        $this->assign('bearing_knowledge',$this->bearing_knowledge());
        //市场动态
        $this->assign('market_dynamics',$this->market_dynamics());
        //产品知识
        $this->assign('products_information',$this->products_information());
        //友情链接
        $this->assign('friendlink',$this->friendlink());
    	
    	//获取页头所有数据
        parent::head();
        //获取页尾所有数据
        parent::foot();

        return $this->fetch();
    }

    /**
    * 搜索页 
    */
    public function catalog(){
        //分类id
        $cat_id                 = input('cat_id');
        //品牌id
        $brand_id               = input('brand_id');
        //搜索关键词
        $keywords               = input('keywords','22213');
        //库存地
        $s_kcd                  = input('s_kcd');
        //产地
        $s_cd                   = input('cd');
        //材质
        $s_cz                   = input('cz');
        //搜索价格
        $s_price                = input('s_price');
        //排序方式
        $order_pattern          = input('order_pattern');
        $order_updown           = input('order_updown'); 
        //价格范围
        $start_price            = input('sp');
        $end_price              = input('ep'); 
        //每页显示条数
        $limit                  = input('limit','30'); 
        //标题,关键词,描述
        $this_page_title        = ''; 
        $this_page_keywords     = ''; 
        $this_page_description  = ''; 

        //通过分类ID进入
        if($cat_id){
            //查询该类基本信息
            $this_catalog = $this->this_catalog($cat_id);
            //分类下的标题,关键词,描述
            if($this_catalog['page_title']){
                $this_page_title    = $this_catalog['page_title']; 
            }else{
                $this_page_title    = $this_catalog['cat_name'];
            }
            $this_page_keywords     = $this_catalog['page_keywords']; 
            $this_page_description  = $this_catalog['page_description']; 
            //查询该类及所有子类的列表，以供查询商品
            $this_child_catalog_array = $this->list_this_child_catalog($this_catalog);

            if( !empty($this_child_catalog_array) ){
                foreach ($this_child_catalog_array as $val){
                    $this->sql_in .= ",'".$val['cat_id']."'";
                }

                $this->sql_in   = substr($this->sql_in,1);
                $this->qer      = " AND b.`cat_id` in ($this->sql_in) ";
            }
            $this->sqler = $this->qer;

            //分类id
            $this->assign('cat_id',$cat_id);
            //查询该类基本信息
            $this->assign('this_catalog',$this_catalog);
            //分类目录
            $this->assign('this_parent_catalog',$this->this_parent_catalog($cat_id));
            //查询该类的直接子类
            $this->assign('this_son_catalog',$this->this_son_catalog($cat_id));
            //查询该类及所有子类的列表，以供查询商品
            $this->assign('list_this_child_catalog',$this_child_catalog_array);
            //友情链接
            $this->assign('catalog_friendlink',$this->catalog_friendlink($cat_id,'catalog'));
            //复杂条件筛选
            $this->assign('args_conds',$this->args_conds($this_catalog['cat_name']));
        }else{
            $this->assign('cat_id',''); 
        }
        
        //通过品牌ID进入
        if($brand_id){
            $this->x_qer .= "and a.brand_id=$brand_id";

            $brand_info = $this->brand_info($brand_id);

            //品牌下的标题,关键词,描述
            if($brand_info['page_title']){
                $this->assign('this_page_title',$brand_info['page_title']);
            }else{
                $this->assign('this_page_title',$brand_info['brand_name']);
            }
            $this->assign('this_page_keywords',$brand_info['page_keywords']);
            $this->assign('this_page_description',$brand_info['page_description']); 

            //关键词
            $this->assign('brand_id',$brand_id);
            //品牌资料
            $this->assign('brand_info',$brand_info);
            //友情链接
            $this->assign('catalog_friendlink',$this->catalog_friendlink($brand_id,'brand'));
        }else{
            $this->assign('brand_id',''); 
        }

        //通过关键词搜索
        if($keywords){
            if($s_kcd != ''){
                $this->x_qer .= " and a.x_kcd='$s_kcd' ";
            }
            if($s_price != ''){
                $s_price_array = explode('-', $s_price); 
                $this->x_qer .= " and a.real_price>='".$s_price_array[0]."' and a.real_price<='".$s_price_array[1]."'";
            }

            $ft_string = $this->ft_string($keywords);

            //查询关键词相关的所有品牌
            $this->assign('pp_array',$this->pp_array($ft_string));
            //友情链接
            $this->assign('catalog_friendlink',$this->catalog_friendlink($keywords,'keywords'));
        }else{
            if($s_kcd != ''){
                $this->x_qer .= " AND a.x_kcd='$s_kcd' ";
            }
            if($s_cd != ''){
                $this->x_qer .= " AND a.x_cd='$s_cd' ";
            }
            if($s_cz != ''){
                $this->x_qer .= " AND a.x_cz='$s_cz' ";
            }  
        }

        //排序方式
        if($order_pattern != ''){
            // 销量排序
            if($order_pattern=='sale'){
                $this->oer = "a.`sale_number` DESC";
            }
            // 新品排序
            elseif($order_pattern=='new'){
                $this->oer = "a.`goods_id` DESC";
            }
            // 推荐排序
            elseif($order_pattern=='hot'){
                $this->oer = "a.`is_hot` DESC";
            }
            // 价格排序
            elseif($order_pattern=='price'){
                if($order_updown=='up'){
                    $this->oer = "a.`real_price` DESC";
                }
                if($order_updown=='down'){
                    $this->oer = "a.`real_price` ASC";
                }
            } 
        } 

        //价格范围
        if($start_price != '' && $end_price != ''){
            $this->sqler .= "AND a.`real_price`>='$start_price' AND a.`real_price`<='$end_price' ";
        }

        //主查询语句
        $query_rows = Db::name('t_goods_thread')->alias('a')->join('t_goods_relate b','a.goods_id=b.goods_id','LEFT')->join('t_brand c','a.brand_id=c.brand_id','LEFT')->where("a.is_sale=1 $this->sqler $this->x_qer")->limit("$limit")->select();
        
        $sql = Db::getLastSql();

        //统计商品总数
        $total_number = Db::name('t_goods_thread')->alias('a')->join('t_goods_relate b','a.goods_id=b.goods_id','LEFT')->where("a.is_sale=1 $this->sqler $this->x_qer")->count();

        //统计搜索不到的关键词
        if($query_rows==null && $keywords!=null && $cat_id=='' && $brand_id=='' && $s_kcd=='' && $s_price=='' && $nav=='' && $s_cd=='' && $s_kcd=='' && $s_cz==''){
            $this->search_notes($keywords);
        }

        //搜索结果
        $this->assign('query_rows',$query_rows);
        //价格区间
        $this->assign('price_array',$this->price_array());
        //生成价格区间标签
        $this->assign('price_range',$this->price_range($s_price));
        //统计商品总数
        $this->assign('total_number',$total_number);
        //关键词
        $this->assign('keywords',$keywords);
        //出产地
        $this->assign('s_kcd',$s_kcd);
        //材质
        $this->assign('s_cz',$s_cz);
        //产地
        $this->assign('s_cd',$s_cd);
        //价格
        $this->assign('s_price',$s_price);
        
        //获取页头所有数据
        parent::head();

        //获取页尾所有数据
        parent::foot();

        return $this->fetch();
    }

    /**
    * 复杂条件筛选 
    */
    private function args_conds($cat_name){
        $args_conds = Db::name('t_goods_cond')->where("x_yjfl='".$cat_name."' or x_ejfl='".$cat_name."' or x_sjfl='".$cat_name."'")->group('cond_name')->select();
        foreach($args_conds as $key=>$val){
            $cond_name = $val['cond_name'];
            $cond_name = str_replace('材质', 's_cz', $cond_name);
            $cond_name = str_replace('产地', 's_cd', $cond_name);
            $cond_name = str_replace('品牌', 's_pp', $cond_name);
            $cond_name = str_replace('库存地', 's_kcd', $cond_name);

            $args_cond_items = Db::name('t_goods_cond')->where("`cond_name`='".$val['cond_name']."' and (`x_yjfl`='$cat_name' or `x_ejfl`='$cat_name' or `x_sjfl`='$cat_name')")->group('cond_value')->select();

            $args_conds[$key] = $args_cond_items; 
        }

        array_push($args_conds,$cond_name);

        return $args_conds;
    }  

    /**
    * 生成价格区间标签 
    */
    private function price_range($s_price){
        $price_arr = '';

        $price_array = $this->price_array();

        $min_price = round($price_array['min_price'],2);
        $tmp_price = round($price_array['max_price']/5, 2);

        for($i=1; $i<=5; $i++){
            if($i-1==0){
                $p1 = ($tmp_price*($i-1)+$min_price).'-'.($tmp_price*($i)); 
                if($p1!='0-0'){
                    if($s_price == $p1){ 
                        $class = 'class="cond_hover" style="color:white"';
                    }else{
                        $class = '';
                    }
                    $price_arr .= '<li class="search_li"><a '.$class.' href="javascript:set_s_cond('.'s_price'.','.$p1.')">'.$p1.'</a></li>';
                }
            } else { 
                $p2 = ($tmp_price*($i-1)).'-'.($tmp_price*($i));
                if($p2!='0-0'){
                    if($s_price == $p2){ 
                        $class = 'class="cond_hover" style="color:white"';
                    }else{
                        $class = '';
                    }
                    $price_arr .= '<li class="search_li"><a '.$class.' href="javascript:set_s_cond('.'s_price'.','.$p2.')">'.$p2.'</a></li>';
                }
            }
        }  

        return $price_arr;
    }  

    /**
    * 价格区间 
    */
    private function price_array(){
        $price_array = Db::name('t_goods_thread')->alias('a')->join('t_goods_relate b','a.goods_id=b.goods_id','LEFT')->field('MIN(real_price) AS min_price, MAX(real_price) AS max_price')->where('a.is_sale=1 '.$this->sqler)->limit('1')->find();

        return $price_array;
    }  

    /**
    * 统计搜索不到的关键词 
    */
    private function search_notes($keywords){
        $note_id = Db::name('t_search_notes')->where('keywords='.$keywords)->vlaue('note_id');
        
        if( !empty($note_id) ){
            $sql = "update t_search_notes set frequency=(frequency+1) where note_id=".$note_id;
            Db::name('t_search_notes')->where('note_id',$note_id)->update(['frequency' => '(frequency+1)']);
        }else{
            Db::name('t_search_notes')->insert(['keywords'=>'$keywords','frequency'=>1]);
        }
    }  

    /**
    * 搜索页面友链 
    */
    private function catalog_friendlink($id,$page){
        switch($page){
            case 'catalog';
                $catalog_friendlink = Db::name('t_friendlink')->where("page=$page and cat_id=$id")->order('order_id asc');
                break;
            case 'brand';
                $catalog_friendlink = Db::name('t_friendlink')->where("page=$page and brand_id=$id")->order('order_id asc');
                break;
            case 'keywords';
                $catalog_friendlink = Db::name('t_friendlink')->where("page=$page and keywords=$id")->order('order_id asc');
                break;
        }

        return $catalog_friendlink;
    }  

    /**
    * 查询该类及所有子类的列表，以供查询商品 
    */
    private function list_this_child_catalog($this_catalog){
        $this_child_catalog_array = array();

        $catalog = Db::name('t_goods_catalog')->field('cat_id,cat_name')->where('parent_id='.$this_catalog['cat_id'])->order('order_id asc')->select();
         
        if( $catalog != '' ){
            foreach ($catalog as $val){  
                $this_child_catalog_array[] = $val; 

                self::list_child_catalog($val);
            }
        }

        return $this_child_catalog_array;
    }  

    private function list_child_catalog($this_catalog){
        $catalog = Db::name('t_goods_catalog')->field('cat_id,cat_name')->where('parent_id='.$this_catalog['cat_id'])->order('order_id desc')->select();

        return $catalog;
    }

    /**
    * 查询关键词相关的所有品牌 
    */
    private function pp_array($ft_string){
        $pp_array = Db::table('t_goods_thread')->field('a.brand_id as brand_id,b.brand_name as brand_name,b.type as type,count(*) as c')->alias('a')->join('t_brand b','a.brand_id = b.brand_id','LEFT')->where('a.is_sale=1 and MATCH (a.ft) AGAINST ("$ft_string" IN BOOLEAN MODE)')->group('a.brand_id')->select();

        return $pp_array;
    }  

    /**
    * 生成全文搜索字符 
    */
    private function ft_string($keywords){
        $ft_string = '';
        $chars_array = parent::ft_split($keywords);

        foreach ($chars_array as &$val) {
            $ft_string .= '+'.$val.' ';
        }

        $this->sqler .= "AND MATCH (a.ft) AGAINST ('".$ft_string."' IN BOOLEAN MODE) ";

        return $ft_string;
    }  

    /**
    * 查询该类基本信息 
    */
    private function this_catalog($cat_id){
        $this_catalog = Db::name('t_goods_catalog')->where('cat_id='.$cat_id)->find();

        if($this_catalog['cat_name'] == ''){
            $this_catalog['cat_name'] = '全部';
        }

        return $this_catalog;
    }  

    /**
    * 查询该类的直接子类 
    */
    private function this_son_catalog($cat_id){
        $this_son_catalog = Db::name('t_goods_catalog')->where('parent_id='.$cat_id)->order('order_id asc')->select();

        return $this_son_catalog;
    }  

    /**
    * 分类目录 
    */
    private function this_parent_catalog($cat_id){
        /// 查询该类的全部父类
        $this_parent_catalog = array();
        if($cat_id > 0){
            // 执行遍历所有父类
            $this_parent_catalog = $this->list_parent_catalog( $cat_id ); 
        }

        return $this_parent_catalog;
    }  

    private function list_parent_catalog( $cat_id  ){
        $this_row = Db::name('t_goods_catalog')->field('cat_id,cat_name,parent_id')->where('cat_id='.$cat_id)->find();  

        if( $this_row['cat_id'] != '' ){
            $this->this_parent_catalog[] = $this_row; 
        }

        if( $this_row['parent_id'] != '' && $this_row['parent_id'] != '0' ){  
            $this->list_parent_catalog($this_row['parent_id']); 
        }

        return $this->this_parent_catalog;
    }

    /**
    * 品牌资料 
    */
    private function brand_info($brand_id){
        $brand_info = Db::name('t_brand')->where('brand_id='.$brand_id)->find();

        return $brand_info;
    }  

    /**
    * banner 
    */
    private function banner(){
        if(Cache::get('banner') == ''){
            $banner = Db::name('t_site_ppt')->where('zhan_id=0')->order('order_id desc')->select();
            Cache::set('banner',$banner);
        }

        return Cache::get('banner');
    }

    /**
    * 最新采购信息 
    */
    private function purchase_info(){
    	if(Cache::get('purchase_info') == ''){
    		$purchase_info = Db::name('t_customer_purchase')->order('id desc')->limit('8')->select();

			foreach($purchase_info as $key=>$val){
				$purchase_info[$key]['message'] = $val['address'].$val['user_name'].'求购'.$val['goods_name'];
			}

			Cache::set('purchase_info',$purchase_info);
    	}

    	return Cache::get('purchase_info');
    }

    /**
    * 最新公告 
    */
    private function new_notice(){
        if(Cache::get('new_notice') == ''){
            $new_notice = Db::name('t_article_thread')->field('thread_id,title')->where('cat_id=980')->order('thread_id desc')->limit('8')->select();

            Cache::set('new_notice',$new_notice);
        }

        return Cache::get('new_notice');
    }

    /**
    * 促销信息 
    */
    private function promotional_information(){
        if(Cache::get('promotional_information') == ''){
            $promotional_information = Db::name('t_article_thread')->field('thread_id,title')->where('cat_id=981')->order('thread_id desc')->limit('8')->select();

            Cache::set('promotional_information',$promotional_information);
        }

        return Cache::get('promotional_information');
    }

    /**
    * 新闻中心 
    */
    private function news_online(){
        if(Cache::get('news_online') == ''){
            $news_online = Db::name('t_article_thread')->where('cat_id=239')->order('addtime desc')->limit('14')->select();

            Cache::set('news_online',$news_online);
        }

        return Cache::get('news_online');
    }

    /**
    * 行业资讯 
    */
    private function industry_information(){
        if(Cache::get('industry_information') !== ''){
            $industry_information = Db::name('t_article_thread')->field('thread_id,title,addtime')->where('cat_id=377')->order('addtime desc')->limit('10')->select();

            Cache::set('industry_information',$industry_information);
        }

        return Cache::get('industry_information');
    }

    /**
    * 轴承知识 
    */
    private function bearing_knowledge(){
        if(Cache::get('bearing_knowledge') !== ''){
            $bearing_knowledge = Db::name('t_article_thread')->field('thread_id,title')->where('cat_id=160')->order('addtime desc')->limit('12')->select();

            Cache::set('bearing_knowledge',$bearing_knowledge);
        }

        return Cache::get('bearing_knowledge');
    }

    /**
    * 市场动态 
    */
    private function market_dynamics(){
        if(Cache::get('market_dynamics') !== ''){
            $market_dynamics = Db::name('t_article_thread')->field('thread_id,title')->where('cat_id=239')->order('addtime desc')->limit('12,12')->select();

            Cache::set('market_dynamics',$market_dynamics);
        }

        return Cache::get('market_dynamics');
    }

    /**
    * 产品资讯 
    */
    private function products_information(){
        if(Cache::get('products_information') !== ''){
            $products_information = Db::name('t_article_thread')->field('thread_id,title')->where('cat_id=626')->order('addtime desc')->limit('12')->select();

            Cache::set('products_information',$products_information);
        }

        return Cache::get('products_information');
    }

    /**
    * 商品楼层 
    */
    private function goods_floor(){
        if(Cache::get('goods_floor') == ''){
            $m = Db::name('t_goods_catalog');
            $goods_floor = $m->field('cat_id,cat_name')->where('parent_id=0')->order('order_id asc')->select();

            foreach($goods_floor as $key=>$val01){
                //品牌
                $goods_floor[$key]['cat_brand'] = Db::table('t_brand_classify')->alias('a')->join('t_brand w','a.brand_id = w.brand_id','LEFT')->where('a.cat_id='.$val01['cat_id'])->limit('8')->select();

                //二级分类
                $goods_floor[$key]['cat02'] = $m->field('cat_id,cat_name')->where('parent_id='.$val01['cat_id'])->order('order_id asc')->limit('12')->select();
                
                $items_cat_id = '-1';
                
                foreach($goods_floor[$key]['cat02'] as $val02){
                    //三级分类
                    $cat03 = $m->field('cat_id')->where('parent_id='.$val02['cat_id'])->order('order_id asc')->select();
                    
                    foreach ($cat03 as $val03){
                        $items_cat_id .= ','.$val03['cat_id'];  
                    }
                }

                //商品信息
                $goods_floor[$key]['items'] = Db::table('t_goods_thread')->alias('a')->join('t_goods_relate b','a.goods_id = b.goods_id','LEFT')->where("a.is_sale=1 and b.cat_id IN( $items_cat_id )")->order('a.goods_id asc,a.is_hot DESC')->limit('10')->select(); 

                //本周热销商品
                $goods_floor[$key]['hot_goods'] = Db::table('t_goods_thread')->alias('a')->join('t_goods_relate b','a.goods_id = b.goods_id','LEFT')->where("a.is_sale=1 and b.cat_id IN( $items_cat_id )")->order('a.sale_number desc')->limit('5')->select(); 
            }

            Cache::set('goods_floor',$goods_floor);
        }

        return Cache::get('goods_floor');
    }

    /**
    * 友情链接 
    */
    private function friendlink(){
        if(Cache::get('friendlink') == ''){
            $friendlink = Db::name('t_friendlink')->where("page='index'")->order('order_id asc')->select();

            Cache::set('friendlink',$friendlink);
        }

        return Cache::get('friendlink');
    }

    

}
