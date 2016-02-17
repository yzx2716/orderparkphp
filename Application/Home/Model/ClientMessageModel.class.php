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
         $time_cc = S("time_cc");
         if(empty($time_cc)){
             $time_cc = date("H:i:s")."_cc";
             S("time_cc", $time_cc, array("expire"=>3600));
         }
         $time_bb = S("time_bb");
         if(empty($time_bb)){
             $time_bb = date("H:i:s")."_bb";
             S("time_bb", $time_bb, array("expire"=>3600));
         }
         return $time_cc;
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


