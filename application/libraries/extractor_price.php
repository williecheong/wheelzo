<?php

class ExtractorPrice {
	
	public function getPrice($message) {
		$message = $this->cleanUpMessage_price($message);
		//Searching for primary indicators
		$result = $this->findPrimaryIndicators_price($message);
		if ($result['numOfDollarSign'] == 1){
			$result = $this->primaryIndicatorResultAlpha_price($message, $result);
		} else if ( $result['numOfDollarSign'] > 1) {
			//console.log("More than one $ found in the context");
		} else if ( $result['numOfDollarSign'] == 0) {
			//console.log("No $ found in the context");
			return $result;
		}
		return $result;
	}

	protected function cleanUpMessage_price( $message ){
		//String to lower case
		$message = strtolower($message);

		//Clean puntuation
		$message = preg_replace("/(\r\n|\n|\r)/", " ", $message);
		$message = preg_replace("/(\:|\;|\#|\.|\,|\!)/", "", $message);
		$message = preg_replace("/\s+/", " ", $message);

		//String split spaces
		$message = explode(" ", $message);
		return $message;
	}

	protected function findPrimaryIndicators_price( $message ){
		$result = $this->findPrimaryIndicatorsResult_price();
		foreach ($message as $index => $element) {
			if (strpos($element, "$") !== false){
				$result['numOfDollarSign']++;
				$result['indexOfDollarSign'][] = $index;
			}
		}
		return $result;
	}

	protected function findPrimaryIndicatorsResult_price(){
		return array(
			"numOfDollarSign" => 0,
			"indexOfDollarSign" => array(),
			"price" => null
		);
	}

	protected function primaryIndicatorResultAlpha_price( $message, $result ){

		$targetString = $message[$result['indexOfDollarSign'][0]];
		$dollarSignPosition = strpos($targetString, '$');
		if ($dollarSignPosition === false) {
			$dollarSignPosition = -1;
		}

		$dollarSignLeft = substr($targetString, 0, $dollarSignPosition);
		$dollarSignRight = substr($targetString, $dollarSignPosition+1, count($targetString)-$dollarSignPosition+1);

		$dollarSignLeft = intval( preg_replace('/\D/', '', $dollarSignLeft) );
		$dollarSignRight = intval( $dollarSignRight );

		if( $dollarSignRight > 0 && $dollarSignLeft > 0){
			$result['price'] = $dollarSignRight . " ? " . $dollarSignLeft;
		} else if ( $dollarSignRight > 0 ){
			$result['price'] = $dollarSignRight;
		} else if ( $dollarSignLeft > 0){
			$result['price'] = $dollarSignLeft;
		}
		return $result;
	}

	protected function indexOf($array, $word) {
	    foreach($array as $key => $value) {
	        if($value == $word) {
	            return $key;
	        }
	    }
	    return -1;
	}
}



