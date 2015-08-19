<?php

class ExtractorBase {

	protected function indexOf($array, $word) {
	    foreach($array as $key => $value) {
	        if($value == $word) {
	            return $key;
	        }
	    }
	    return -1;
	}

    protected function stringContains($haystack, $needle) {
        return strpos($haystack, $needle) !== false;
    }

}