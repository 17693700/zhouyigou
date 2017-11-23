<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    
    //栏目页
    '[catalog]'     => [
        ':cat_id'   => ['index/Index/catalog', ['method' => 'get'], ['cat_id' => '\d+']],
    ],
    //品牌详情页
    '[brand]'     => [
        ':brand_id'   => ['index/Index/catalog', ['method' => 'get'], ['brand_id' => '\d+']],
    ],
    //商品详情页
    '[thread]'     => [
        ':goods_id'   => ['index/Index/thread', ['method' => 'get'], ['goods_id' => '\d+']],
    ],
    //文章详情页
    '[article]'     => [
        ':thread_id'   => ['index/Index/article', ['method' => 'get'], ['thread_id' => '\d+']],
    ],

];
