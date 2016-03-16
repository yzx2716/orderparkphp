<?php
/**
 * 与客户端（park）下发消息
 */

namespace Home\Model;
use Think\Model;

class ClientMessageModel extends Model {
    
     private $expire_time = 300;  //缓存时间
     
     private $content = array(
         'enter' => '您好：微信预约用户%s请求进入，请放行，谢谢！',
         'out' => '您好：微信预约用户%s请求离开，请放行，谢谢！'
         );


     /**
      * 添加消息
      * @param type $park_id
      * @param type $order_id 
      * @param type $nick_name
      * @param type $tran_type enter|out
      * @param type $cur_time
      * @param type $msg 附加信息
      * @return string
      */
     public function setClientMessage($park_id, $order_id, $nick_name, $tran_type){
         $cur_time = date("Y-m-d H:i:s");
         $content = sprintf($this->content[$tran_type], $nick_name);
         $mes_add = array();
         $mes_add['park_id'] = $park_id;
         $mes_add['order_id'] = $order_id;
         $mes_add['nick_name'] = $nick_name;
         $mes_add['tran_type'] = $tran_type;
         $mes_add['add_time'] = $cur_time;
         $mes_add['content'] = $content;
         $mes_id = M('client_message')->add($mes_add);

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
         
         $park_request[$mes_id] = array('tran_type'=>$tran_type, 'content'=>$content, 'time'=>$cur_time);
         S($cache_key, $park_request, array("expire"=>$this->expire_time));

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
             $park_request = array();
             //有效期以内的数据
             $where = "park_id={$park_id} and add_time>='".date("Y-m-d H:i:s", time()-$this->expire_time)."' and state=1";
             $mes_arr = M('client_message')->where($where)->order('mes_id')->select();
             foreach ($mes_arr as $v){
                 $park_request[$v['mes_id']] = array('tran_type'=>$v['tran_type'],  'content'=>$v['content'], 'time'=>$v['add_time']);
             }
             S($cache_key, $park_request);
         }
         
         //去除超时数据
         if(!empty($park_request)){
             foreach ($park_request as $k=>$v){
                 if(time() - strtotime($v['time']) > $this->expire_time){
                     unset($park_request[$k]);
                 }
             }
         }
         //转成xml
         $mes_xml = $this->arr2xml($park_request);
         return $mes_xml;
     }
     
     /**
      * 生成xml格式
      */
     private function arr2xml($data){
        $str = '<?xml version="1.0" encoding="UTF-8"?>';
        $str .= '<order_info>';    
        foreach ($data as $k => $v) {
            $str .= '<message mes_id="{$k}">';
            foreach ($v as $key=>$val){
                $str .= "<{$key}>{$val}<{$key}/>";
            }
            $str .= "</message>";
        }
        $str .= '</order_info>';
        return $str;
    }

}


