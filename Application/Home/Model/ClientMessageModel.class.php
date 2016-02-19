<?php
/**
 * 与客户端（park）下发消息
 */

namespace Home\Model;
use Think\Model;

class ClientMessageModel extends Model {
    
     private $expire_time = 300;  //缓存时间


     /**
      * 添加消息
      * @param type $park_id
      * @param type $operate_id 
      * @param type $nick_name
      * @param type $action enter|out
      * @param type $time
      * @param type $msg 附加信息
      * @return string
      */
     public function setClientMessage($park_id, $operate_id, $nick_name, $action, $time, $msg){
         //获取缓存数据
         $cache_key = 'park_enter_out_request_'.$park_id;
         $park_request = S($cache_key);
         //去除超时数据
         if(!empty($park_request)){
             foreach ($park_request as $k=>$v){
                 if(time() - strtotime($v['time']) > $this->expire_time){
                     unset($park_request[$k]);
                 }
             }
         }
         $park_request[$operate_id] = array('nick_name'=>$nick_name, 'action'=>$action, 'time'=>$time, 'msg'=>$msg);
         S($cache_key, $park_request, array("expire"=>$this->expire_time));

         print_r($park_request);
         
         return true;
     }
     
     /**
      * 获取消息
      */
     public function getClientMessage($park_id){
         //获取缓存数据
         $cache_key = 'park_enter_out_request_'.$park_id;
         $park_request = S($cache_key);
         if(empty($park_request)){
             
         }
         
     }
     
     /**
      * 生成xml格式
      */
     private function arr2xml($data, $root = true){
        $str = "";
        if ($root){
            $str .= '<?xml version="1.0">';
        }
            
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $child = arr2xml($val, false);
                $str .= "<$key>$child</$key>";
            } else {
                //$str.= "<$key><![CDATA[$val]]></$key>";
                $str.= "<$key>$val</$key>";
            }
        }
        if ($root)
            $str .= "</xml>";
        return $str;
    }

}


