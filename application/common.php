<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
namespace app;
use think\Controller;
use think\facade\Session;
use think\facade\Request;
class Common extends Controller{
    public function __construct()
    {
        $url=Request::url();
        parent::__construct();
        if(!Session::has('role')){
            $this->error('请登录后再进行访问','/login');
        }elseif (Session::get('role')=='管理员'&&$url=='/normal'){
            $this->error('不允许越权访问');
        }elseif (Session::get('role')=='普通用户'&&$url=='/admin'){
            $this->error('不允许越权访问');
        }
    }
}