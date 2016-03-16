<?php
/**
 * 客户端（park）相关操作
 */
namespace Home\Controller;
use Think\Controller;
class ClientController extends Controller {
    
     public function getMessage(){
        //@todo 测试数据
        $str = '<?xml version="1.0" encoding="UTF-8"?>
             <order_info>
             <message mes_id="156">
                <action>enter</action>
                <content>您好：微信预约用户ABC请求进入，请放行，谢谢！</content>
                <time>2016-02-29 11:08:05</time>
             </message>
             <message mes_id="170">
                <action>enter</action>
                <content>您好：微信预约用户ABC请求离开，请放行，谢谢！</content>
                <time>2016-02-29 11:10:05</time>
             </message>
             <message mes_id="175">
                <action>enter</action>
                <content>您好：微信预约用户ABC请求离开，请放行，谢谢！</content>
                <time>2016-02-29 11:11:05</time>
             </message>
             </order_info>';
         echo $str;exit();
         
         
         $park_id = 7;
         $order_message = D('ClientMessage')->getClientMessage($park_id);
         echo $order_message;
    }
    
    /**
     * 客户端操作后上传数据，完成闭环
     */
    public function completeOrder(){
        
        
        //更新operate表
        
        //更新order表
        
    }
}