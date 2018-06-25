<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/4/30
 * Time: 17:51
 */
namespace app\index\controller;

use think\Controller;
use app\index\model\Deal as Dealm;
use think\facade\Request;
class Deal extends Controller{
    public function dealList()
    {
        return $this->fetch('deal-list');
    }
    public function dealData()
    {
        $states=Request::param('states');
        $page=Request::param('page');
        $limit=Request::param('limit');
        if($states==null){
            $states='';
        }
        $results=Dealm::listDeal($page,$limit,$states);
        return $results;
    }
    public function delDeal(){
        $param= json_decode(file_get_contents('php://input'),true);
        $id=array();
        foreach ($param as $k=>$v){
            if (is_array($v)){
                array_push($id,$v['id']);
            }else{
                array_push($id,$param['id']);
                break;
            }
        }
        return Dealm::delDeal($id);
    }
    public function dealEdit($id,$h_id){
        $deal=Dealm::get($id);
        $state=$deal['states'];
        $this->assign('id',$id);
        $this->assign('sta',$state);
        $this->assign('h_id',$h_id);
        return $this->fetch('deal-edit');
    }
    public function editState(){
        $param= json_decode(file_get_contents('php://input'),true);
        return Dealm::editState($param);
    }
}