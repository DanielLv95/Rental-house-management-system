<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/4/23
 * Time: 9:40
 */
namespace app\index\controller;

use think\Controller;
use think\facade\Request;
use app\index\Model\Customer as Customerm;

class Customer extends Controller{
    public function customerList($r)
    {
        $this->assign('r',$r);
        return $this->fetch('customer-list');
    }

    public function customerAdd()
    {
        return $this->fetch('customer-add');
    }


    public function customerEdit($id)
    {
        $customer=Customerm::get($id);
        $this->assign('customer',$customer);
        return $this->fetch('customer-edit');
    }

    public function addCus(){
        $param= json_decode(file_get_contents('php://input'),true);
        return Customerm::addCustomer($param);
    }
    public function upCus(){
        $param= json_decode(file_get_contents('php://input'),true);
        return Customerm::upCustomer($param);
    }

    public function listCus($r){
        $truename=Request::param('truename');
        $page=Request::param('page');
        $limit=Request::param('limit');
        if($truename==null){
            $truename='';
            $results=Customerm::listCustomer($page,$limit,$truename,$r);
        }else{
            $results=Customerm::listCustomer($page,$limit,$truename,$r);
        }
        return $results;
    }


    public function delCus(){
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
        return Customerm::delCustomer($id);
    }
    public function cselect($r){
        return json(Customerm::cusSelect($r));
    }
}