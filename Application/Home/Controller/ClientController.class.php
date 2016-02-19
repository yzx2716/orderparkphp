<?php
/**
 * 客户端（park）相关操作
 */
namespace Home\Controller;
use Think\Controller;
class ClientController extends Controller {
    
     public function getOrderMessage(){
     
//        $dd = get_client_ip();
//        echo $dd;
//        die();
//        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>[ 您现在访问的是Home模块的Index控制器 ]</div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
//        $this->display();
    }
    
    /**
     * 客户端操作后上传数据，完成闭环
     */
    public function completeOrder(){
        
        
        //更新operate表
        
        //更新order表
        
    }
}