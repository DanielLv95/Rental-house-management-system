<?php
/**
 * Created by PhpStorm.
 * Users: 56252
 * Date: 2018/4/3
 * Time: 10:17
 */
namespace app\index\model;

use think\Model;
class UserInfo extends Model{
    public function user()
    {
        return $this->belongsTo('Users','uid');
    }
    public static function upUserInfo($param){
        $userinfo=new UserInfo();
        $idno=$param['idno'];
        $str1=substr($idno,6,4);
        $str2=substr($idno,10,2);
        $str3=substr($idno,12,2);
        $param['birth']= $str1.'-'.$str2.'-'.$str3;
        $date=date('Y');
        $param['age']=$date-$str1;
        $userinfo1=UserInfo::where('idno',$idno)->find();
        if ($userinfo1){
            $result=2;
            return $result;
        }else{
            $result=$userinfo->allowField(true)->save($param,['id'=>$param['id']]);
            return $result;
        }
    }
    public function getRoleAttr($value)
    {
        $status = [1=>'管理员',2=>'普通用户'];
        return $status[$value];
    }
    public function getSexAttr($value)
    {
        $status = [1=>'男',2=>'女'];
        return $status[$value];
    }

}