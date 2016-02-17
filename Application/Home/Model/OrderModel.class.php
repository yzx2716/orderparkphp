<?php
/**
 * 订单的相关操作
 * 订单状态描述：
 * 0：已取消 1：正常 2：已入库 3：已出库
 */

namespace Home\Model;
use Think\Model;

class OrderModel extends Model {
    
    protected $tableName = "order";
    //时间界限
    private $time_range = array(
        'am' => array('min'=>0, 'max'=>15), 
        'pm' => array('min'=>15, 'max'=>24),
        'whole' => array('min'=>0, 'max'=>24),
        'times' => array('min'=>0, 'max'=>24),
        );

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
        //写扫描日志
        $add = array();
        $add['user_id'] = $user_id;
        $add['park_id'] = $park_id;
        $add['tran_type'] = 'enter';
        $add['add_time'] = date("Y-m-d H:i:s");
        M("scan")->add($add);

        //判断申请的有效性，是否已经有订单
        $where = array();
        $where['user_id'] = $user_id;
        $where['park_id'] = $park_id;
        $where['to_date'] = date("Y-m-d");
        $where['state'] = array('in', array(1, 2)); //容错，入口可重复扫描
        $order_detail = $this->where($where)->field("order_id, day_type")->find();   

        //订单不存在
        if(empty($order_detail)){
            return array('state'=>0, 'msg'=>'no_order');
        }
        //时间段不符
        $time_range = $this->time_range;
        $time_range_s = $time_range[$order_detail['day_type']];
        if(date("H") > $time_range_s['max'] || date("H") < $time_range_s['min']){
            return array('state'=>0, 'msg'=>'not_in_time');
        }
        
        //更新订单状态，一天多次不需要更新
        if('times' != $order_detail['day_type']){
            $save = array('state'=>2, 'upd_time'=>date("Y-m-d H:i:s"));
            $this->where("order_id=".$order_detail['order_id'])->save($save);
        }
        
        //下发消息
        
        
        return array('state'=>1, msg=>'enter_permit');
    }
    
    /**
     * 申请出库
     */
    public function outRequest($user_id, $park_id){
        //写扫描日志
        $add = array();
        $add['user_id'] = $user_id;
        $add['park_id'] = $park_id;
        $add['tran_type'] = 'out';
        $add['add_time'] = date("Y-m-d H:i:s");
        M("scan")->add($add);

        //判断申请的有效性，是否已经有订单
        $where = array();
        $where['user_id'] = $user_id;
        $where['park_id'] = $park_id;
        $where['to_date'] = array('elt', date("Y-m-d")); //查最近的一个单
        $where['state'] = array('in', array(2, 3)); //容错，入口可重复扫描
        $order_detail = $this->where($where)->field("order_id, day_type, to_date")->order("order_id desc")->find();   

        //订单不存在
        if(empty($order_detail)){
            return array('state'=>0, 'msg'=>'no_order');
        }
        //订单超时计费
        D("Charge")->overtimeCharge($order_detail['order_id']);
        
        //更新订单状态，一天多次不需要更新
        if('times' != $order_detail['day_type']){
            $save = array('state'=>3, 'upd_time'=>date("Y-m-d H:i:s"));
            $this->where("order_id=".$order_detail['order_id'])->save($save);
        }
        
        return array('state'=>1, msg=>'out_permit');
    }
}


