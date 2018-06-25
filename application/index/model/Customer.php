<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2018/4/23
 * Time: 9:31
 */
namespace app\index\model;

use think\Model;
use think\model\concern\SoftDelete;
class Customer extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function house(){
        return $this->hasMany('House','landlord');
    }
    public function zu(){
        return $this->hasMany('Deal','zu_id');
    }
    public function qiuzu(){
        return $this->hasMany('Deal','qiu_id');
    }
    public static function addCustomer($array)
    {
        $customer = new Customer();
        $customer->sex=$array['sex'];
        $customer->truename=$array['name'];
        $customer->idno=$array['idno'];
        $str1=substr($array['idno'],6,4);
        $str2=substr($array['idno'],10,2);
        $str3=substr($array['idno'],12,2);
        $birth=$str1.'-'.$str2.'-'.$str3;
        $customer->birth=$birth;
        $date=date('Y');
        $age=$date-$str1;
        $customer->age=$age;
        $customer->address=$array['address'];
        $customer->phone=$array['phone'];
        $customer->email=$array['email'];
        $customer->role=$array['role'];
        $customer1=Customer::where('idno',$array['idno'])->find();
        //0 成功
        if($customer1){
            $err=2;
        }
        if ($customer1==null){
            if ($customer->save()){
                Log::addLog('添加客户，客户姓名'.$array['name']);
                $err=0;
            }else{
                $err=1;
            };
        }
//        dump($err);
        return json($err);
    }
    public static function upCustomer($param){
        $id=$param['id'];
        $customer=new Customer;
        $idno=$param['idno'];
        $str1=substr($param['idno'],6,4);
        $str2=substr($param['idno'],10,2);
        $str3=substr($param['idno'],12,2);
        $param['birth']=$str1.'-'.$str2.'-'.$str3;
        $date=date('Y');
        $param['age']=$date-$str1;
        $customer1=Customer::where('idno',$idno)->find();
        if ($customer1){
            $result=2;
            return $result;
        }else{
            $result=$customer->allowField(true)->save($param,['id'=>$id]);
            Log::addLog('编辑客户');
            return $result;
        }
    }

    public static function listCustomer($page, $limit,$truename,$role)
    {
        $offset = ($page - 1) * $limit;
        if($truename==''){
            $list = Customer::limit($offset, $limit)->where('role',$role)->select()->toArray();
            $count = Customer::where('role',$role)->count();
        }else{
            $list = Customer::limit($offset, $limit)->whereLike('truename','%'.$truename.'%')->where('role',$role)->select()->toArray();
            $count = Customer::whereLike('truename','%'.$truename.'%')->where('role',$role)->count();
        }
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

    public static function delCustomer($id){
        $result=array();
        Customer::startTrans();
        for ($i=0;$i<count($id);$i++){
            array_push($result,Customer::destroy($id[$i]));
        }
        foreach ($result as $k=>$v){
            if($v==0){
                Customer::rollback();
                return 0;
                break;
            }
        }
        Customer::commit();
        Log::addLog('删除客户');
        return 1;
    }
    public static function cusSelect($r){
        $list=Customer::where('role',$r)->select();
        return $list;
    }
    public function getSexAttr($value)
    {
        $status = [1=>'男',2=>'女'];
        return $status[$value];
    }
}