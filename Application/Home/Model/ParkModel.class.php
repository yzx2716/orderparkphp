<?php
/**
 * park相关操作
 */

namespace Home\Model;
use Think\Model;

class ParkModel extends Model {
    
    protected $tableName = "park";
    
    /**
     * 根据id获取park的相关信息
     */
    public function getParkInfo($park_id, $field='*'){
        $info = $this->where("park_id=".$park_id)->field($field)->find();
        return $info;
    }
}


