<?php
/**
 * 预约（park）相关操作
 */
namespace Home\Controller;
use Think\Controller;
class YuyueController extends Controller {
    
    public function _initialize(){
         
    }
    
    /**
     * 可预约park列表展示
     */
    public function parkList(){
        
    }

    /**
     * 预约展示页
     */
    public function setOrder(){
        
        //@todo 测试数据 
        $park_id = 1000;
        
        
        $park_name = D('Park')->getParkInfo($park_id, $field='name');
        $this->assign('park_name', $park_name['name']);
        
        $this->display();
    }
    
    /**
     * 确认预约
     */
    public function doSetOrder(){
        //@todo 测试数据 
        $park_id = 1000;
        $user_id = 123;
        
        $order_time = array(
            array('to_date'=>'2016-02-25', 'day_type'=>'whole'),
            array('to_date'=>'2016-02-28', 'day_type'=>'whole'),
        );
    }
}