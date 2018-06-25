<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/5/27
 * Time: 19:38
 */
namespace app\index\model;
use think\Model;
use think\facade\Session;
class Notice extends Model{
    public static function addNotice($param){
        $title=$param['title'];
        $detail=$param['detail'];
        $danger=$param['danger'];
        $user=Session::get('username');
        $date=date('Y-m-d H:i:s');
        $notice=new Notice([
            'title'=>$title,
            'detail'=>$detail,
            'datetime'=>$date,
            'user'=>$user,
            'danger'=>$danger
        ]);
        Log::addLog('发布公告');
        return $notice->save();
    }
    public static function listNotice(){
        return Notice::order('datetime','desc')->select();
    }
}