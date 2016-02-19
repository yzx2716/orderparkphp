<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'order_park', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'op_', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符集
    //@todo memcache缓存配置 线上可以暂时用file缓存
    'DATA_CACHE_TYPE' => 'Memcache',
    'MEMCACHE_HOST' => '127.0.0.1',
    'MEMCACHE_PORT'	=>	'11211',
);