<?php
/**
 * 用户（消费者）计费相关操作
 */

namespace Home\Model;
use Think\Model;

class ChargeModel extends Model {
    
    /**
     * 预约计费
     */
    public function orderCharge($order_id){
        
    }
    
    /**
     * 超时计费
     */
    public function overtimeCharge($order_id){
        //判断是否超时
        
        //计费
    }
}


