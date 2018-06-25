<?php
/**
 * Created by PhpStorm.
 * Users: 56252
 * Date: 2018/3/31
 * Time: 17:36
 */

namespace app\index\model;

use think\Model;
use think\facade\Session;
use think\model\concern\SoftDelete;

class Users extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function userInfo()
    {
        return $this->hasOne('UserInfo', 'uid')->setEagerlyType(0)->joinType('LEFT');
    }

    public static function checkLogin($username, $userpwd)
    {

        $user= Users::where('username',$username)->with('UserInfo')->find();
        if ($user==null){
            $u['code']=1;
            return $u;
        }
        if ($user['userpwd']==md5($userpwd)) {
            Session::set('username', $username);
            Session::set('id', $user['id']);
            Session::set('role', $user['user_info']['role']);
            $u['code']=0;
            $u['role']=$user['user_info']['role'];
            return $u;
        } else {
            $u['code']=1;
            return $u;
        }
    }

    public static function addUser($array)
    {
        $user = new Users();
        $user->username=$array['username'];
        $user->userpwd=md5($array['pass']);
        $userinfo=new UserInfo();
        $userinfo->sex=$array['sex'];
        $userinfo->truename=$array['name'];
        $userinfo->idno=$array['idno'];
        $str1=substr($array['idno'],6,4);
        $str2=substr($array['idno'],10,2);
        $str3=substr($array['idno'],12,2);
        $birth=$str1.'-'.$str2.'-'.$str3;
        $userinfo->birth=$birth;
        $date=date('Y');
        $age=$date-$str1;
        $userinfo->age=$age;
        $userinfo->address=$array['address'];
        $userinfo->phone=$array['phone'];
        $userinfo->email=$array['email'];
        $userinfo->role=$array['role'];
        $user->userInfo=$userinfo;
        $user1=Users::where('username',$array['username'])->find();
        $userinfo1=UserInfo::where('idno',$array['idno'])->find();
        //0 成功
        if($user1){
            $err=2;
        }elseif ($userinfo1){
            $err=3;
        }
        if ($user1==null&&$userinfo1==null){
            if ($user->together('userInfo')->save()){
                Log::addLog('添加用户');
                $err=0;
            }else{
                $err=1;
            };
        }
//        dump($err);
        return json($err);
    }

    public static function listUser($page, $limit,$username)
    {
        $offset = ($page - 1) * $limit;
        if($username==''){
            $list = Users::limit($offset, $limit)->with('UserInfo')->select()->toArray();
            $count = Users::count();
        }else{
            $list = Users::limit($offset, $limit)->with('UserInfo')->whereLike('username','%'.$username.'%')->select()->toArray();
            $count = Users::whereLike('username','%'.$username.'%')->count();
        }
        $result=array();
        if (count($list)==0){
            $code = 1;
            $msg = '无结果';
        }else{
            foreach ($list as $k => $v) {
                unset($v["userpwd"]);
                $userinfo = $v['user_info'];
                unset($v['user_info']);
                unset($userinfo['id']);
                $arrmer = array_merge($v, $userinfo);
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
    public static function delUser($id){
        $result=array();
        Users::startTrans();
        for ($i=0;$i<count($id);$i++){
                array_push($result,Users::destroy($id[$i]));
            }
            foreach ($result as $k=>$v){
                if($v==0){
                    Users::rollback();
                    return 0;
                    break;
                }
            }
        Users::commit();
        Log::addLog('删除用户');
        return 1;
        }
        public static function changePwd($param){
            $opwd=$param['opass'];
            $npwd=$param['npass'];
            $user=Users::get(Session::get('id'));
            if (md5($opwd)==$user->userpwd){
                $user->userpwd=md5($npwd);
                if($user->save()){
                    return 0;
                    Log::addLog('修改密码');
                }else{
                    return 1;
                }
            }else{
                return 2;
            }
        }
}