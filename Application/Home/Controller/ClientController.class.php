<?php
/**
 * 客户端（park）相关操作
 */
namespace Home\Controller;
use Think\Controller;
class ClientController extends Controller {
    
     public function getMessage(){
         //@todo 测试数据
         $str = '<?xml version="1.0">
             <order_info>
             <message mes_id=156>
                <nick_name>test_nick_name_1</nick_name>
                <tran_type>enter</tran_type>
                <time>2016-02-29 11:08:05</time>
                <msg></msg>
             </message>
             <message mes_id=170>
                <nick_name>test_nick_name_1</nick_name>
                <tran_type>enter</tran_type>
                <time>2016-02-29 11:10:05</time>
                <msg></msg>
             </message>
             <message mes_id=175>
                <nick_name>test_nick_name_2</nick_name>
                <tran_type>out</tran_type>
                <time>2016-02-29 11:12:05</time>
                <msg></msg>
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