<?php
/**
 * 用户（customer）| 客户端(park) 相关操作记录
 */

namespace Home\Model;
use Think\Model;

class OperateModel extends Model {
    
    protected $tableName = 'operate';
    
    /**
     * 用户申请出入库操作记录
     */
    public function userOrderOperate($user_id, $park_id, $order_id, $tran_type, $cur_time){
        //一个订单一种操作只记录一条
        $where = array('order_id' => $order_id, 'tran_type'=>$tran_type);
        $operate_id = $this->where($where)->getField('operate_id');
        if(empty($operate_id)){
            $add = array();
            $add['park_id'] = $park_id;
            $add['user_id'] = $user_id;
            $add['order_id'] = $order_id;
            $add['tran_type'] = $tran_type;
            $add['add_time'] = $cur_time;
            $operate_id = $this->add($add);
        }else{
            //更新写入时间
            $this->where($where)->setField("add_time", $cur_time);
        }
        
        return $operate_id;
    }
    
}


