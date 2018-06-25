<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
Route::rule('login','login/index');
Route::rule('dologin','login/dologin');
Route::rule('logout','login/loginout');

Route::rule('admin','user/admin');
Route::rule('normal','user/normal');
Route::rule('changepwd/[:b]','user/changePwd');
Route::rule('memberlist','user/memberlist');
Route::rule('userlist','user/listuser');
Route::rule('memberedit/:id','user/memberEdit');
Route::rule('deluser','user/delUser');
Route::rule('upuser','user/upUserInfo');
Route::rule('adduser','user/addUser');
Route::rule('memberadd','user/memberAdd');
Route::rule('memdetail/:id','user/memberDetail');

Route::rule('houselist','house/houseList');
Route::rule('addhouse','house/addHouse');
Route::rule('uphouse','house/upHouse');
Route::rule('housedata','house/houseData');
Route::rule('delhouse','house/delHouse');
Route::rule('yuhouse','house/yuHouse');
Route::rule('houseyu/:id','house/houseyu');
Route::rule('housedetail/:id','house/houseDetail');

Route::rule('customerlist/:r','customer/customerList');
Route::rule('/cuslist/:r','customer/listCus');
Route::rule('customeredit/:id','customer/customerEdit');
Route::rule('delcus','customer/delCus');
Route::rule('addcus','customer/addCus');
Route::rule('upcus','customer/upCus');
Route::rule('customeradd','customer/customerAdd');
Route::rule('cusselect/:r','customer/cselect');

Route::rule('deallist','deal/dealList');
Route::rule('dealdata','deal/dealData');
Route::rule('dealedit/:id/:h_id','deal/dealEdit');
Route::rule('deldeal','deal/delDeal');
Route::rule('editstate','deal/editState');
Route::rule('upload','house/upload');

Route::rule('addnotice','notice/add');
Route::rule('listnotice','notice/listNotice');

Route::rule('listlog','log/index');

return [

];
