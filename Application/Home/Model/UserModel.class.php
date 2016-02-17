<?php
/**
 * 用户（消费者）信息相关操作
 * user表中的wechat字段，目前限制非空。如果后期有非微信的情况，用前缀wechat_加一个用户名或具体考虑
 */

namespace Home\Model;
use Think\Model;

class UserModel extends Model {
    
    protected $tableName = "user";
    /**
     * 用户注册
     */
    public function register($wechat_id){
        $add = array();
        $add['wechat_id'] = $wechat_id;
        $add['add_time'] = date("Y-m-d H:i:s");
        $user_id = $this->add($add);
        session("user_id", $user_id);
        return $user_id;
    }
}


