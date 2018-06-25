<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 2018/4/29
 * Time: 21:18
 */
namespace app\index\model;

use think\Model;
use think\model\concern\SoftDelete;
class Deal extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function house(){
        return $this->belongsTo('House','h_id');
    }

    public static function addDeal($hid,$qid){
        $deal=new Deal();
        $date=date('Y-m-d H:i:s');
        $list=House::get($hid);
        $zu=Customer::get($list['landlord']);
        $zu_name=$zu['truename'];
        $qiu=Customer::get($qid);
        $qiu_name=$qiu['truename'];
        $deal->h_id=$hid;
        $deal->zu=$zu_name;
        $deal->qiu=$qiu_name;
        $deal->date=$date;
        $deal->states=0;
        return $deal->save();
    }
    public static function listDeal($page,$limit,$states){
        $offset = ($page - 1) * $limit;
        if($states==''){
            $list = Deal::limit($offset, $limit)->with('House')->select()->toArray();
            $count = Deal::count();
        }else{
            $list = Deal::limit($offset, $limit)->with('House')->where('states',$states)->select()->toArray();
            $count = Deal::whereLike('states',$states)->count();
        }
        $result=array();
        if (count($list)==0){
            $code = 1;
            $msg = '无结果';
        }else{
            foreach ($list as $k => $v) {
                $house= $v['house'];
                unset($v['house']);
                unset($house['id']);
                unset($house['states']);
                $arrmer = array_merge($v, $house);
                $result []= $arrmer;
            }
            $code = 0;
            $msg = '';
        }
        $data = [
            'code' => $code,
            'msg' => $msg,
            'count' => $count,
            'data' => $result
        ];
        return json($data);
    }
    public static function delDeal($id){
        $result=array();
        Deal::startTrans();
        for ($i=0;$i<count($id);$i++){
            array_push($result,Deal::destroy($id[$i]));
        }
        foreach ($result as $k=>$v){
            if($v==0){
                Deal::rollback();
                return 0;
                break;
            }
        }
        Deal::commit();
        Log::addLog('删除交易');
        return 1;
    }
    public static function editState($param){
        $states=$param['states'];
        $id=$param['id'];
        $h_id=$param['h_id'];
        $deal=Deal::get($id);
        $house=House::get($h_id);
        if ($states==1){
            $house->states=2;
        }
        if ($states==2){
            $house->states=3;
        }
        if ($states==3){
            $house->states=0;
        }
        $deal->states=$states;
        Deal::startTrans();
        if($deal->save()&&$house->save()){
            Deal::commit();
            Log::addLog('更改交易状态');
            return 1;
        }else{
            Deal::rollback();
            return 0;
        }
    }
    public function getStatesAttr($value){
        $status = [0=>'已预订',1=>'洽谈中',2=>'交易成功',3=>'交易失败'];
        return $status[$value];
    }
}