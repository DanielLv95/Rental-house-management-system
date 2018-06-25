<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/5/30
 * Time: 17:58
 */
namespace app\index\controller;

use think\Controller;
use app\index\model\Log as Logm;
use think\facade\Request;
class Log extends Controller{
    public function index(){
        $page=Request::param('page');
        $limit=Request::param('limit');
        if($page==''){
            return $this->fetch('index');
        }else{
            return Logm::logList($page,$limit);
        }

    }
}