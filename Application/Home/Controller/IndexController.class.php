<?php
/**
 * 用户相关操作
 */
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    
     public function index(){
     
//        $dd = get_client_ip();
//        echo $dd;
//        die();
//        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>[ 您现在访问的是Home模块的Index控制器 ]</div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
//        $this->display();
    }
    
    /**
     * 用户下单
     */
    public function addOrder(){
        //判断订单是否已存在，防止订单重复
        
        //写入订单
    }
    
    /**
     * 扫描请求入库出库
     */
    public function enterOut(){
        //根据传入字段判断出库/入库
        
        
        //入库申请
        //todo 示例数据
        $user_id = 3;
        $park_id = 7;
        $result = D("Order")->enterRequest($user_id, $park_id);
   
        print_r($result);
        
        //出库申请
        //todo 示例数据
//        $user_id = 3;
//        $park_id = 7;
//        $result = D("Order")->outRequest($user_id, $park_id);
//        
//        print_r($result);
        
        
//        $park_id = 2;
//        $user_id = 2;
//        $wechat_id = 123123;
//        $nick_name = 'aabbcc';
//        $action = 'enter';
//        $time = date("Y-m-d H:i:s");
//        $msg = 'let me in';
//        
//        $aa = D("ClientMessage")->setClientMessage($park_id, $user_id, $wechat_id, $nick_name, $action, $time, $msg);
        
        
//        $park_id = 2;
//        $bb = D("ClientMessage")->getClientMessage($park_id);
//        print_r($bb);
            
    }
    
    
}