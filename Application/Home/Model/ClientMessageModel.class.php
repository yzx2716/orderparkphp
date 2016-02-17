<?php
/**
 * 与客户端（park）下发消息
 */

namespace Home\Model;
use Think\Model;

class ClientMessageModel extends Model {
    
    /**
     * 添加消息
     */
     public function setClientMessage(){
         //获取缓存数据
         $time = S("time_aa");
         if(empty($time)){
             $time = date("H:i:s");
             S("time_aa", $time);
         }
         return $time;
     }
     
     /**
      * 获取消息
      */
     public function getClientMessage(){
         
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


