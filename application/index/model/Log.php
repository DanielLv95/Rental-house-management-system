<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/5/30
 * Time: 17:32
 */
namespace app\index\model;

use think\Model;
use think\facade\Session;
class Log extends Model{
    public static function addLog($detail){
        $date=date('Y-m-d H:i:s');
        $log=new Log([
            'username'=>Session::get('username'),
            'detail'=>$detail,
            'time'=>$date
        ]);
        $log->save();
    }
    public static function logList($page,$limit){
        $offset = ($page - 1) * $limit;
        $list = Log::limit($offset, $limit)->order('time','desc')->select()->toArray();
        $count=count(Log::select());
        if (count($list)==0){
            $code = 1;
            $msg = '无结果';
        }else{
            $code = 0;
            $msg = '';
        }
        $data = [
            'code' => $code,
            'msg' => $msg,
            'count' => $count,
            'data' => $list
        ];
        return json($data);
    }
}