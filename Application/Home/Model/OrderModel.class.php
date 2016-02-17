<?php
/**
 * 订单的相关操作
 */

namespace Home\Model;
use Think\Model;

class OrderModel extends Model {
    
    protected $tableName = "order";
    /**
     * 下单，单个下单
     * @param type $user_id 用户id
     * @param type $park_id 停车场id
     * @param type $to_date 预约日期
     * @param type $day_type 午别
     * @return type
     */
    public function addOrder($user_id, $park_id, $to_date, $day_type){
        $add = array();
        $add['user_id'] = $user_id;
        $add['park_id'] = $park_id;
        $add['to_date'] = $to_date;
        $add['day_type'] = $day_type;
        $add['add_time'] = date("Y-m-d H:i:s");
        $order_id = $this->add($add);
        return $order_id;
    }
    
    /**
     * 申请入库
     */
    public function enterRequest($user_id, $park_id){
        
        $add = array();
        $add['user_id'] = $user_id;
        $add['park_id'] = $park_id;
        $add['tran_type'] = 'enter';
        $add['add_time'] = date("Y-m-d H:i:s");
        //判断申请的有效性，是否已经有订单
        $where = array();
        $where['user_id'] = $user_id;
        $where['park_id'] = $park_id;
        $where['to_date'] = date("Y-m-d");
        $where['state'] = 1;
        $day_type = $this->where($where)->getField("day_type"); 
        echo $this->getLastSql();
        echo $day_type;
    }
}


