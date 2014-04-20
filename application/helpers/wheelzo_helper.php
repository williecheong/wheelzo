<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('encode_to_chinese') ) {    
    function encode_to_chinese( $integer ) {
        $chinese_int = array(
           '零', '一', '二', '三', '四', '五', '六', '七', '八', '九'
        );

        $encoded_integer = '第';
        
        $individual_integers = str_split($integer);
        foreach ( $individual_integers as $individual_integer ) {
            if ( isset($chinese_int[$individual_integer]) ) {
                $encoded_integer .= $chinese_int[$individual_integer];
            } else {
                $encoded_integer .= '怪';
            }
         }

        $encoded_integer .= '个';

        return $encoded_integer;
    }
}