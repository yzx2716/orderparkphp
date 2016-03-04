<?php
/**
 * 预约（park）相关操作
 */
namespace Home\Controller;
use Think\Controller;
class YuyueController extends Controller {
    //用户id
     private $user_id;

     public function _initialize(){
         //todo 测试数据
        session('user_id', 3);
        $this->user_id = session('user_id');
    }
    
    /**
     * 可预约park列表展示
     */
    public function parkList(){
        
        
        
    }

    /**
     * 预约下单页
     */
    public function setOrder(){
        $park_id = intval($_GET['park_id']);
        
        $park_name = D('Park')->getParkInfo($park_id, $field='name');
        $this->assign('park_id', $park_id);
        $this->assign('park_name', $park_name['name']);
        
        $time_range = D('Park')->getParkTimeRangePage($park_id);
        $this->assign($time_range);
        
        $this->display();
    }
    
    /**
     * 确认预约
     */
    public function doSetOrder(){
        if(count($_POST['day_type']) > 200){
            $this->error("单次预约过多！", U('Yuyue/setOrder'));
        }
        $day_type_arr = array_unique($_POST['day_type']);
        $park_id = intval($_GET['park_id']);
        $user_id = $this->user_id;
        $result = D('Order')->addOrder($user_id, $park_id, $day_type_arr);
        if($result){  //预约成功跳转
            $this->success("预约成功！", U('Yuyue/orderList'));
        }else{ //预约失败
            $error = D('Order')->getError() ? D('Order')->getError() : "预约失败！";
            $this->error($error, U('Yuyue/setOrder'));
        }
    }
    
    /**
     * 订单列表
     */
    public function orderList(){
        $order_list = D('Order')->orderList($this->user_id);
        $this->assign('order_list', $order_list);
        $this->display();
    }
}