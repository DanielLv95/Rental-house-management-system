<?php
/**
 * Created by PhpStorm.
 * Users: 56252
 * Date: 2018/4/17
 * Time: 10:04
 */

namespace app\index\controller;

use app\index\model\House as Housem;
use think\Controller;
use think\facade\Request;
use think\Image;

class House extends Controller
{
    public function houseList()
    {
        return $this->fetch('house-list');
    }
    public function houseYu($id)
    {
        $this->assign('id',$id);
        return $this->fetch('house-yu');
    }
    public function houseData()
    {
        $states=Request::param('states');
        $page=Request::param('page');
        $limit=Request::param('limit');
        if($states==null){
            $states='';
        }
        $results=Housem::listHouse($page,$limit,$states);
        return $results;
    }
    public function houseDetail(){
        $id=Request::param('id');
        $house=Housem::get($id);
        $detail=$house['detail'];
        $customer=$house['customer'];
        $this->assign('house',$house);
        $this->assign('id',$id);
        $this->assign('detail',$detail);
        $this->assign('customer',$customer);
        return $this->fetch('house-detail');
    }
    public function addHouse()
    {
        $param= json_decode(file_get_contents('php://input'),true);

        if ($param == null) {
            return $this->fetch('house-add');
        } else {
            return Housem::addHouse($param);
        }
    }
    public function upHouse(){
        $param= json_decode(file_get_contents('php://input'),true);
        return Housem::editHouse($param);
    }
    public function delHouse(){
        $param= json_decode(file_get_contents('php://input'),true);
        $id=array();
        foreach ($param as $k=>$v){
            if (is_array($v)){
                if($v['states']=='未出租'){
                    array_push($id,$v['id']);
                }
            }else{
                if($v['states']=='未出租'){
                    array_push($id,$param['id']);
                }
                break;
            }
        }
        if(count($id)==0){
            return 0;
        }else{
            return Housem::delHouse($id);
        }
    }
    public function yuHouse(){
        $param= json_decode(file_get_contents('php://input'),true);
        return Housem::yudHouse($param);
    }
    public function upload()
    {
        $code = 0;
        $msg = '';
        $data='';
        $file = Request::file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size' => 5242880, 'ext' => 'jpeg,jpg,png,gif'])->move("./uploads");
        if ($info) {
            $url = str_replace('\\', '/', $info->getPathName());
            $image = Image::open($url);
            $thumb_url = './uploads/'.substr($url,10,9).substr($url,19,32).'_sm.jpg';
            if ($image->thumb('300', '300')->save($thumb_url)) {
                $data = [
                    'src' => $thumb_url,
                    'title' => '缩略图'
                ];
            } else {
                $code = 1;
                $msg = $file->getError();
                unlink($url);
                unlink($thumb_url);
            }
        }
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}