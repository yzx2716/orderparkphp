<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//字符串截取，防止乱码
function mbstr($string, $start, $length, $suffix=true,$encoding="UTF-8"){
    $new_string = mb_substr($string, $start, $length, $encoding);
    if($suffix){
        $str_len = mb_strlen($string, $encoding);
        if($str_len > $length){
            $new_string .= '...';
        }
    }
    return $new_string;
}

