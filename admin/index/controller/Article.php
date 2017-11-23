<?php
namespace app\index\controller;

use think\Controller;
use think\File;
use think\Db;

class Article extends Common{

    public function article_list(){
        $qer        = '';
        $qer2       = '';
        $and        = '';
        $cat_id     = input('cat_id');
        $keywords   = input('keywords');

        if($cat_id || $keywords){
            if($cat_id){
                $qer .= 'a.cat_id='.$cat_id;
            }

            if($keywords){
                $qer2 .= "a.title like('%".$keywords."%')";
            }

            if($cat_id && $keywords){
                $and = ' and ';
            }

            $all_article = Db::name('t_article_thread')->alias('a')->join('t_article_catalog b','a.cat_id=b.cat_id','LEFT')->where($qer.$and.$qer2)->paginate(20);

            $count_article = Db::name('t_article_thread')->alias('a')->join('t_article_catalog b','a.cat_id=b.cat_id','LEFT')->where($qer.$and.$qer2)->count();
        }else{
            $all_article = Db::name('t_article_thread')->alias('a')->join('t_article_catalog b','a.cat_id=b.cat_id','LEFT')->paginate(20);

            $count_article = Db::name('t_article_thread')->alias('a')->join('t_article_catalog b','a.cat_id=b.cat_id','LEFT')->count();
        }

        $all_catalog = $this->article_catalog();

        $this->assign('all_catalog',$all_catalog);
        $this->assign('all_article',$all_article);
        $this->assign('count_article',$count_article);
        parent::_header();
        return $this->fetch();
    }

    public function article_add(){
        $all_catalog = $this->article_catalog();

        $this->assign('all_catalog',$all_catalog);
        return $this->fetch();  
    }

    public function article_edit(){
        $thread_id   = input('thread_id');

        $all_catalog = $this->article_catalog();
        $article     = Db::name('t_article_thread')->where('thread_id='.$thread_id)->find();

        $this->assign('article',$article);
        $this->assign('all_catalog',$all_catalog);
        return $this->fetch();  
    }

    public function add(){
        $data                       = array();
        $data['cat_id']             = input('cat_id');
        $data['title']              = input('title');
        $data['summary']            = input('summary');
        $data['order_id']           = input('order_id');
        $data['content']            = input('editorValue');
        $data['image']              = $this->image_add('image');
        $data['thumbnail']          = '';
        $data['is_hot']             = '';
        $data['clicks']             = '';
        $data['page_keywords']      = '';
        $data['page_description']   = '';
        $data['addtime']            = date('Y-m-d H:i:s');

        $res = Db::name('t_article_thread')->insert($data);

        if($res){
            $this->success('添加成功！','article_list','',1);
        }else{
            $this->error('添加失败！','article_add','',1);
        }
    }

    public function edit(){
        $thread_id                  = input('thread_id');

        $data                       = array();
        $data['cat_id']             = input('cat_id');
        $data['title']              = input('title');
        $data['summary']            = input('summary');
        $data['order_id']           = input('order_id');
        $data['content']            = input('editorValue');
        $data['thumbnail']          = '';
        $data['is_hot']             = '';
        $data['clicks']             = '';
        $data['page_keywords']      = '';
        $data['page_description']   = '';
        $data['addtime']            = date('Y-m-d H:i:s');

        $image                = parent::image_add('image');

        if($image != ''){
            $data['image']    = $image;
        }else{
            $data['image']    = input('prev_image');
        }

        $res = Db::name('t_article_thread')->where("thread_id=".$thread_id)->update($data);

        if($res){
            $this->success('修改成功！','article_list','',1);
        }else{
            $this->error('修改失败！','article_list','',1);
        }
    }

    public function del(){
        $thread_id                  = input('thread_id');

        $m = Db::name('t_article_thread');

        $image = $m->where('thread_id',$thread_id)->value('image');
        if($image != ''){
            $res1 = unlink('./uploads/'.$image);
        }else{
            $res1 = true;
        }

        $res2 = $m->delete($thread_id);

        if($res1 && $res2){
            $this->success('删除成功！','article_list','',1);
        }else{
            $this->error('删除失败！','article_list','',1);
        }
    }

    public function some_del(){
        $idAll = input('idAll');
        $m     = Db::name('t_article_thread');

        $del_arr = explode(',',$idAll);

        foreach($del_arr as $val){
            if($val != ''){
                $image = $m->where('thread_id',$val)->value('image');
                if($image != '') {
                    $res1 = unlink('./uploads/'.$image);
                }else{
                    $res1 = true;
                }
                
                $res2 = $m->where('thread_id',$val)->delete();
            }
        }

        if($res1 && $res2){
            $this->success('删除成功！','article_list','',1);
        }else{
            $this->error('删除失败！','article_list','',1);
        }
    }

    public function article_catalog(){
        return Db::name('t_article_catalog')->field('cat_id,cat_name')->order('order_id asc')->select();
    }

}
