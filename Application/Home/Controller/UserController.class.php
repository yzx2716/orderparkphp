<?php
/**
 * 用户相关操作
 */
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    
    /**
     * 个人中心
     */
    public function index(){
        $this->display();
    }

}