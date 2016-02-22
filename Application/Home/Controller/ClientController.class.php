<?php
/**
 * 客户端（park）相关操作
 */
namespace Home\Controller;
use Think\Controller;
class ClientController extends Controller {
    
     public function getMessage(){
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