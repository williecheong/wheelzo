<?php

require_once "extractor_base.php";
require_once "extractor_time.php";
require_once "extractor_price.php";
require_once "extractor_location.php";

class Extractor extends ExtractorBase {

	public function getRideFromMessage($message, $posting_date) {
		if (!$this->is_driving($message)) {
			return null;
		}

		$this->extractor_time = new ExtractorTime();
		$this->extractor_price = new ExtractorPrice();
		$this->extractor_location = new ExtractorLocation();

		$location_result = $this->extractor_location->getLocations($message);
		$price_result = $this->extractor_price->getPrice($message);

		return array(
			"origin" => ucfirst($location_result['origin']),
	 		"destination" => ucfirst($location_result['destination']),
	 		"departure" => $this->extractor_time->getTime($message, $posting_date),
	 		"price" => intval($price_result['price']),
	 		"capacity" => 2
 		);
	}

	protected function is_driving($message) {
	    $message = strtolower($message);
		
	    $passengerHintWords = array("looking", "need");
	    foreach ($passengerHintWords as $hintWord) {
	    	if ($this->stringContains($message, $hintWord)) {
	    		if (strpos($message, $hintWord) < strlen($message)*0.6) {
		    		return false;
	    		}
	    	}
	    }
	    $driverHintWords = array("driving", "leaving", "available", "seats");
	    foreach ($driverHintWords as $hintWord) {
	    	if ($this->stringContains($message, $hintWord)) {
	    		return true;
	    	}
	    }	    
	    return true; // we are now unsure
	}
}