<?php
/**
 * Created by PhpStorm.
 * Users: 56252
 * Date: 2018/4/17
 * Time: 11:10
 */
namespace app\index\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\index\model\Deal;
class House extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function customer(){
        return $this->belongsTo('Customer','landlord');
    }
    public function deal()
    {
        return $this->hasMany('Deal', 'h_id');
    }
    public static function addHouse($param){
        $title=$param['title'];
        $main=substr_replace($param['mainpic'],'',0,1);
        $rent=$param['rent'];
        $address=$param['address'];
        $detail=str_replace('src="./','src="/',$param['detail']);
        $detail=str_replace('\\','',$detail);
        $fangdong=$param['fangdong'];
        $house=new House([
            'title'=>$title,
            'main_pic'=>$main,
            'address'=>$address,
            'detail'=>$detail,
            'rent'=>$rent,
            'landlord'=>$fangdong
        ]);
        Log::addLog('添加房屋信息');
        return $house->save();
    }
    public static function editHouse($param){
        $id=$param['id'];
        $title=$param['title'];
        $main=$param['mainpic'];
        $rent=$param['rent'];
        $address=$param['address'];
        $detail=str_replace('\\','',$param['detail']);
        $house=House::get($id);
        $house->title=$title;
        $house->main_pic=$main;
        $house->address=$address;
        $house->detail=$detail;
        $house->rent=$rent;
        Log::addLog('编辑房屋信息');
        return $house->save();
    }
    public static function listHouse($page,$limit,$states){
        $offset = ($page - 1) * $limit;
        if($states==''){
            $list = House::limit($offset, $limit)->with('Customer')->select()->toArray();
            $count = House::count();
        }else{
            $list = House::limit($offset, $limit)->with('Customer')->where('states',$states)->select()->toArray();
            $count = House::whereLike('states',$states)->count();
        }
        $result=array();
        if (count($list)==0){
            $code = 1;
            $msg = '无结果';
        }else{
            foreach ($list as $k => $v) {
                $customer = $v['customer'];
                unset($v['customer']);
                unset($customer['id']);
                $arrmer = array_merge($v, $customer);
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
    public static function delHouse($id){
        $result=array();
        House::startTrans();
        for ($i=0;$i<count($id);$i++){
            array_push($result,House::destroy($id[$i]));
        }
        foreach ($result as $k=>$v){
            if($v==0){
                House::rollback();
                return 0;
                break;
            }
        }
        House::commit();
        Log::addLog('删除房屋信息');
        return 1;
    }
    public static function yudHouse($param){
        $hid=$param['hid'];
        $qid=$param['qiuzu'];
        $house=House::get($hid);
        if ($house['states']!='未出租'){
            return 2;
        }
        House::startTrans();
        $house->states=1;
        if($house->save()){
            if (Deal::addDeal($hid,$qid)){
                House::commit();
                Log::addLog('预定房屋');
                return 0;
            }else{
                House::rollback();
            }
        }
        return 1;
    }
    public function getStatesAttr($value){
        $status = [0=>'未出租',1=>'已预订',2=>'洽谈中',3=>'已出租'];
        return $status[$value];
    }
}