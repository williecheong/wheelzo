<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Use this instead of file_get_contents
// For compatibility with hosting services
// E.g. Dreamhost. Stackato?
if ( ! function_exists('rest_curl') ) {    
    function rest_curl( $url, $type = "GET", $params = array() ) {
        $ch = curl_init();
        $timeout = 10; // set to zero for no timeout
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        if ( $type == "POST" ) {
            $postData = json_encode($params);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
                array(
                    'Content-Type: application/json'
                )
            );
        }

        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}

if ( ! function_exists('pluralize') ) {    
    function pluralize( $amount, $singular = '', $plural = 's' ) {
        if ( $amount == 1 )
            return $singular;
        else
            return $plural;
    }
}

if ( ! function_exists('extract_city') ) {    
    function extract_city( $input = '' ) {
        $commaExplode = explode(',', $input);
        if ( !isset($commaExplode[0]) ) { // If it is not found, we have a problem
            return $input;
        }

        $spaceExplode = explode(' ', $commaExplode[0]);
        if ( !isset($spaceExplode[0]) ) {
            return $commaExplode[0];
        }

        return $spaceExplode[0];
    }
}

if ( ! function_exists('string_contains') ) {
    function string_contains($needle, $haystack) {
        return strpos($haystack, $needle) !== false;
    }
}

if ( ! function_exists('shorten_string') ) {
    function shorten_string( $string, $size ) {
        if (strlen($string) > $size) {
            return substr($string, 0, $size-3) . "... ";
        } else {
            return $string;
        }
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

if ( ! function_exists('array_to_object') ) {   
    function array_to_object($d) {
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return (object) array_map(__FUNCTION__, $d);
        }
        else {
            // Return object
            return $d;
        }
    }
}

if ( ! function_exists('indent') ) {
    /**
    * Indents a flat JSON string to make it more human-readable.
    * @param string $json The original JSON string to process.
    * @return string Indented version of the original JSON string.
    */
    function indent($json) {
        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '  ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;

        for ($i=0; $i<=$strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element,
            // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }
        return $result;
    }
}
