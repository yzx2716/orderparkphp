<?php
/**
 * park相关操作
 */

namespace Home\Model;
use Think\Model;

class ParkModel extends Model {
    
    protected $tableName = "park";
    
    //park预约时间界限
    public $time_range_defualt = array(
        'am' => array('min'=>'0:00', 'max'=>'15:00'), 
        'pm' => array('min'=>'12:00', 'max'=>'24:00'),
        'whole' => array('min'=>'0:00', 'max'=>'24:00'),
        'times' => array('min'=>'0:00', 'max'=>'24:00'),
        );
    
    /**
     * 根据id获取park的相关信息
     */
    public function getParkInfo($park_id, $field='*'){
        $info = $this->where("park_id=".$park_id)->field($field)->find();
        return $info;
    }
    
    /**
     * 获取park的预约时间界限
     * 暂时只获取默认设置
     */
    public function getParkTimeRange($park_id){
        return $this->time_range_defualt;
    }
    
    /**
     * 获取park的预约时间界限
     * 页面输出格式
     */
    public function getParkTimeRangePage($park_id){
        $time_range = $this->getParkTimeRange($park_id);
        $page_range = array();
        foreach ($time_range as $k=>$v){
            $page_range[$k] = implode('-', $v);
        }
        $page_range['times'] = '一天多次';
        return $page_range;
    }
}
