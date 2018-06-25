<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/5/27
 * Time: 19:36
 */
namespace app\index\controller;

use think\Controller;
use app\index\model\Notice as Noticem;
class Notice extends Controller{
    public function add(){
        $param= json_decode(file_get_contents('php://input'),true);
        if ($param == null) {
            return $this->fetch('add');
        } else {
            return Noticem::addNotice($param);
        }
    }
    public function listNotice(){
        $list=Noticem::listNotice();
        $this->assign('notice',$list);
        $this->assign('total',count($list));
        return $this->fetch('list');
    }
}