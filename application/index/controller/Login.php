<?php
/**
 * Created by PhpStorm.
 * Users: 56252
 * Date: 2018/3/29
 * Time: 20:18
 */
namespace app\index\controller;

use think\Controller;
use app\index\model\Users;
use think\facade\Session;
use think\captcha\Captcha;
use app\index\model\Log;
class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function verify(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    20,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    false,
            'imageH'    =>      50,
            'imageW'    =>      150,
            'reset'     =>      true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
    // 处理登录逻辑
    public function doLogin()
    {
        $captcha = new Captcha();
        $param= json_decode(file_get_contents('php://input'),true);
        // 验证
        $check=Users::checkLogin($param['username'],$param['userpwd']);
        if (!$captcha->check($param['captcha'])){
            $result=[
                'code'=>2,
                'url'=>'login'
            ];
        }else{
            if($check['code']==1){
                $result=[
                    'code'=>1,
                    'url'=>'login'
                ];
            }else{
                $role=$check['role'];
                if ($role=='管理员'){
                    Log::addLog('管理员登录');
                    $result=[
                        'code'=>0,
                        'url'=>'admin'
                    ];
                }else{
                    Log::addLog('普通用户登录');
                    $result=[
                        'code'=>0,
                        'url'=>'normal'
                    ];
                }
            }
        }
        return json($result);
    }

    // 退出登录
    public function loginOut()
    {
        Session::delete('username');
        Session::delete('role');
        Session::delete('id');

        $this->redirect(url('/login'));
    }
}