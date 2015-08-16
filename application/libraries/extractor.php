<?php

class Extractor {

	public function getRideFromMessage($message, $posting_date) {
		if (!$this->is_driving($message)) {
			return null;
		}

		$location_result = $this->location_tracker($message);

		return array(
			"origin" => ucfirst($location_result['origin']),
	 		"destination" => ucfirst($location_result['destination']),
	 		"departure" => $this->time_tracker($message, $posting_date),
	 		"price" => $this->price_tracker($message),
	 		"capacity" => null
 		);
	}

	protected function is_driving($message) {
	    /*
	    $message = $message.replace(/(\r\n|\n|\r)/gm," ");
	    $message = $message.replace(/(\(|\)|\:|\;|\#|\.|\/|\,|\!|\-)/gm," ");
	    $message = $message.replace(/\s+/g," ");
	    $message = $message.toLowerCase();
		*/

	    $passengerHintWords = array("looking", "need");
	    foreach ($passengerHintWords as $hintWord) {
	    	if (contains($hintWord, $message)) {
	    		return false;
	    	}
	    }
	    $driverHintWords = array("driving", "leaving", "available", "seats");
	    foreach ($driverHintWords as $hintWord) {
	    	if (contains($hintWord, $message)) {
	    		return true;
	    	}
	    }	    
	    // we are now unsure...
	    return true;
	}

	protected function location_tracker($message) {
		$locations = array(
			"origin" => "",
			"destination" => ""
		);

		return $locations;
	}

	protected function time_tracker($message, $posting_date) {
		$departure = "";
		return $departure;
	}


	protected function price_tracker($message) {
		$price = 0;
		return $price;
	}

	private function contains($needle, $haystack) {
	    return (strpos($haystack, $needle) !== false);
	}
}