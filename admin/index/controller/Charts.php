<?php
namespace app\index\controller;

class Charts extends Common{

    public function charts_1(){
        parent::_header();
        return $this->fetch();
    }

}
