<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Use this instead of file_get_contents
// For compatibility with hosting services
// E.g. Dreamhost. Stackato?
if ( ! function_exists('rest_curl') ) {    
    function rest_curl( $url, $type = "GET" ) {
        $ch = curl_init();
        $timeout = 10; // set to zero for no timeout
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}

if ( ! function_exists('clean_input') ) {    
    function clean_input( $data = '' ) {
        if ( is_array($data) || is_object($data) ) {
            foreach ($data as $key => $value) {
                $data[$key] = clean_input( $value );
            }

        } elseif ( is_string($data) ) {
            $data = trim($data);
            $data = htmlspecialchars($data);
        } 

        return $data;
    }
}

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