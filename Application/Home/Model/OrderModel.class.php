<?php
/**
 * 订单的相关操作
 * 订单状态描述：
 * 0：已取消 1：正常 2：已入库 3：已出库
 * order表的state只负责用户（customer）和服务器的操作，不要混入client端操作
 * @important : 扫描请求出库入库必须支持多次
 */

namespace Home\Model;
use Think\Model;

class OrderModel extends Model {
    
    protected $tableName = "order";
    
    protected $order_append = "order_append";


    //提示语
    private $tips = array(
        'no_order' => '无今日订单，请先进行预约！',
        'not_in_time' => '订单不在预约时间范围内！',
        'enter_permit' => '操作成功，欢迎光临！',
        'out_permit' => '操作成功，谢谢惠顾，期待下次再来！',
        'enter_repeat' => '您的订单无法一天多次使用！',
        'system_error' => '系统错误！',
        'operate_fail' => '操作失败！',
        'operate_success' => '操作成功!',
        'order_used' => '订单已使用，无法取消!',
        'order_invalid' => '无效订单！', 
        );
    
    //订单状态描述
    private $state_desc = array(1=>'正常', 2=>'进行中', 3=>'已完成');

    /**
     * 申请入库
     */
    public function enterRequest($user_id, $park_id){
        $tips = $this->tips;
        $cur_time = date("Y-m-d H:i:s");
        $tran_type = 'enter';

        //判断申请的有效性，是否已经有订单
        $where = array();
        $where['user_id'] = $user_id;
        $where['park_id'] = $park_id;
        $where['to_date'] = date("Y-m-d");
        $where['is_cancel'] = 0;
 //       $where['state'] = array('in', array(1, 2)); //容错，入口可重复扫描
        $order_detail = $this->where($where)->field("order_id, day_type")->find();   

        //订单不存在
        if(empty($order_detail)){
            return array('state'=>0, 'msg'=>$tips['no_order']);
        //已经出库
        }elseif (3 == $order_detail['state']) {
            return array('state'=>0, 'msg'=>$tips['enter_repeat']);
        }
        //时间段不符
        $time_range = D('Park')->getParkTimeRange($park_id);
        $time_range_s = $time_range[$order_detail['day_type']];
        if(date("H:i") > $time_range_s['max'] || date("H:i") < $time_range_s['min']){
            return array('state'=>0, 'msg'=>$tips['not_in_time']);
        }
        
        //记录操作
//        $operate_id = D("Operate")->userOrderOperate($user_id, $park_id, $order_detail['order_id'], $tran_type, $cur_time);
        
        //开始计费
        D("Charge")->enterCharge($order_detail['order_id']);
        //更新订单状态
        $save = array('state'=>2, 'upd_time'=>$cur_time);
        $this->where("order_id=".$order_detail['order_id'])->save($save);
        
        //下发消息
        $msg = ''; //@todo 待定
        $user_info = D('User')->getUserInfoById($user_id, "wechat_id, nick_name");
        $nick_name = !empty($user_info['nick_name']) ? $user_info['nick_name'] : $user_info['wechat_id'];
        D("ClientMessage")->setClientMessage($park_id, $order_detail['order_id'], $nick_name, $tran_type, $cur_time, $msg);
        
        return array('state'=>1, msg=>$tips['enter_permit']);
    }
    
    /**
     * 申请出库
     */
    public function outRequest($user_id, $park_id){
        $tips = $this->tips;
        $cur_time = date("Y-m-d H:i:s");
        $tran_type = 'out';

        //判断申请的有效性，是否已经有订单
        $where = array();
        $where['user_id'] = $user_id;
        $where['park_id'] = $park_id;
        $where['to_date'] = array('elt', date("Y-m-d")); //查最近的一个单
        $where['state'] = array('in', array(2, 3)); //容错，入口可重复扫描
        $where['is_cancel'] = 0;
        $order_detail = $this->where($where)->field("order_id, day_type, to_date")->order("order_id desc")->find();   

        //订单不存在
        if(empty($order_detail)){
            return array('state'=>0, 'msg'=>$tips['no_order']);
        }
        
        $order_id = $order_detail['order_id'];
        //记录操作
//        $operate_id = D("Operate")->userOrderOperate($user_id, $park_id, $order_detail['order_id'], $tran_type, $cur_time);
        
        //完成计费
        D("Charge")->outCharge($order_id);
        
        //更新订单状态，一天多次更新到初始状态 这样有利于客户端的统计
        $order_state = 'times' == $order_detail['day_type'] ? 1 : 3;
        $save = array('state'=>$order_state, 'upd_time'=>$cur_time);
        $upd_result = $this->where("order_id=".$order_id)->save($save);
        if(!$upd_result){
            return array('state'=>0, msg=>$tips['system_error']);
        }
        //多次使用的订单，更新使用次数
        if('times' == $order_detail['day_type']){
            $model_append = M($this->order_append);
            $append_order_info = $model_append->where("order_id=".$order_id)->field('order_id, use_count')->find();
            if(!empty($append_order_info)){
                $model_append->where("order_id=".$order_id)->save(array('use_count'=>$append_order_info[use_count]+1, 'upd_time'=>$cur_time));
            }else{
                $model_append->add(array('order_id'=>$order_id, 'use_count'=>1, 'upd_time'=>$cur_time));
            }
        }
        
        //下发消息
        $msg = ''; //@todo 待定
        $user_info = D('User')->getUserInfoById($user_id, "wechat_id, nick_name");
        $nick_name = !empty($user_info['nick_name']) ? $user_info['nick_name'] : $user_info['wechat_id'];
        D("ClientMessage")->setClientMessage($park_id, $order_id, $nick_name, $tran_type, $cur_time, $msg);
        
        return array('state'=>1, msg=>$tips['out_permit']);
    }
    
    /**
     * 用户下单
     * 同一类型的（同一用户，park,日期，时段）订单只能有一个
     */
    public function addOrder($user_id, $park_id, $day_type_arr){
        $add_all = array();
        foreach ($day_type_arr as $v){
            $add = array();
            $add['user_id'] = $user_id;
            $add['park_id'] = $park_id;
            $info = explode(":", $v);
            $add['to_date'] = $info[0];
            $add['day_type'] = $info[1];
            //判断订单是否已存在
            $where = $add;
            $where['is_cancel'] = 0;
            $order_id = $this->where($where)->getField('order_id');
            if($order_id > 0){
                continue;
            }
            
            $add['add_time'] = date("Y-m-d H:i:s");
            $add_all[] = $add;
        }
        if(empty($add_all)){
            $this->error = "订单已存在！";
            return false;
        }
        return $this->addAll($add_all);
    }
    
    /**
     * 获取用户订单
     * 有效订单，以今天为开始时间
     */
    public function orderList($user_id, $page=1, $psize=1000){
        $where = array();
        $where['user_id'] = $user_id;
        $where['is_cancel'] = 0;
        $where['to_date'] = array("egt", date('Y-m-d'));
        //关于排序，不考虑既有上午，又有下午，又有全天的奇葩状况
        $field = "order_id, park_id, to_date, day_type, state";
        $order_list = $this->field($field)->where($where)->order("to_date")->page($page, $psize)->select(); 
        $d_park = D('Park');
        $state_desc = $this->state_desc;
        foreach ($order_list as $k=>$v){
            $time_range = $d_park->getParkTimeRangePage($v['park_id']);
            $order_list[$k]['day_type_page'] = $time_range[$v['day_type']];
            $order_list[$k]['state_desc'] = $state_desc[$v['state']];
            $park_info = $d_park->getParkInfo($v['park_id'], "name, address");
            $order_list[$k]['park_name'] = mbstr($park_info['name'], 0, 16);
            $order_list[$k]['address'] = mbstr($park_info['address'],0, 22);
        }
        return $order_list;
    }
    
    /**
     * 获取历史订单
     * @todo 当前一次获取全部，后期改为infinite滚动获取
     */
    public function historyOrderList($user_id){
        
    }
    
    /**
     * 取消订单
     */
    public function cancelOrder($order_id, $user_id){
        $tips = $this->tips;
        //判断订单的有效性
        $where = array();
        $where['order_id'] = $order_id;
        $where['user_id'] = $user_id;
        $where['is_cancel'] = 0;
        $where['state'] = 1;
        $order_info = $this->where($where)->field('day_type')->find();
        if(empty($order_info)){
            return array('state'=>0, 'msg'=>$tips['order_invalid']);
        }
        //一天多次的订单，使用过后，不允许取消
        if('times' == $order_info['day_type']){
            $use_count = M($this->order_append)->where(array('order_id' => $order_id))->getField('use_count');
            if($use_count > 0){
                return array('state'=>0, 'msg'=>$tips['order_used']);
            }
        }
        //取消订单
        $upd = $this->where(array('order_id' => $order_id))->save(array('is_cancel'=>1, 'upd_time'=>date('Y-m-d H:i:s')));
        if($upd){
            return array('state'=>1, 'msg'=>$tips['operate_success']);
        }else{
            return array('state'=>0, 'msg'=>$tips['operate_fail']);
        }
    }
}


