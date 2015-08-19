<?php

require_once "extractor_time.php";
require_once "extractor_price.php";
require_once "extractor_location.php";

class Extractor {

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
	 		"capacity" => null
 		);
	}

	protected function is_driving($message) {
	    $message = strtolower($message);
		
	    $passengerHintWords = array("looking", "need");
	    foreach ($passengerHintWords as $hintWord) {
	    	if (string_contains($hintWord, $message)) {
	    		return false;
	    	}
	    }
	    $driverHintWords = array("driving", "leaving", "available", "seats");
	    foreach ($driverHintWords as $hintWord) {
	    	if (string_contains($hintWord, $message)) {
	    		return true;
	    	}
	    }	    
	    return true; // we are now unsure
	}
}