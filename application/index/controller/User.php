<?php
/**
 * Created by PhpStorm.
 * Users: 56252
 * Date: 2018/4/2
 * Time: 11:29
 */
namespace app\index\controller;

use app\Common;
use app\index\model\UserInfo;
use think\facade\Request;
use app\index\model\Users;
class User extends Common {


    public function admin()
    {
        return $this->fetch('admin');
    }
    public function normal()
    {
        return $this->fetch('normal');
    }
    public function welcome()
    {
        return $this->fetch();
    }
    public function memberList()
    {
        return $this->fetch('member-list');
    }
    public function memberAdd()
    {
        return $this->fetch('member-add');
    }
    public function memberEdit()
    {
        $id=Request::param('id');
        $user=Users::get($id,'userInfo');
        $this->assign('user',$user);
        $this->assign('info',$user['user_info']);
        return $this->fetch('member-edit');
    }
    public function memberDetail($id)
    {
        $user=Users::get($id,'userInfo');
        $this->assign('user',$user);
        $this->assign('info',$user['user_info']);
        return $this->fetch('member-detail');
    }
    public function addUser(){
        $param= json_decode(file_get_contents('php://input'),true);
        return Users::addUser($param);
    }
    public function listUser(){
        $username=Request::param('username');
        $page=Request::param('page');
        $limit=Request::param('limit');
        if($username==null){
            $username='';
            $results=Users::listUser($page,$limit,$username);
        }else{
            $results=Users::listUser($page,$limit,$username);
        }
        return $results;
    }
    public function delUser(){
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
        return Users::delUser($id);
    }
    public function changePwd($b){
        if($b==1){
            return $this->fetch('changepwd');
        }else{
            $param= json_decode(file_get_contents('php://input'),true);
            return Users::changePwd($param);
        }
    }
    public function upUserInfo(){
        $param= json_decode(file_get_contents('php://input'),true);
        return UserInfo::upUserInfo($param);
    }
}

