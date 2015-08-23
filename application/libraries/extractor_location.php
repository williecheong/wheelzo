<?php

class ExtractorLocation extends ExtractorBase {

	public function getLocations($message) {
		$message = $this->cleanUpMessage($message);
		$result = $this->findPrimaryIndicators($message);

		// Alpha case, presumebly we do not need to search for secondary indicators
		if ($result['numOfFrom'] > 0 && $result['numOfTo'] > 0) {
			$result = $this->primaryIndicatorResultAlpha($message, $result);
		} else if ($result['numOfFrom'] > 0 && $result['numOfTo'] == 0 ){ // Missing 'to'
			if ( $result['numOfArrow'] == 0 ){
				// Try to match with location keywords dictionary
				// We are missing 'to' or '>' indicators
			} else if ($result['numOfArrow'] > 0) {
				// This should not be gamma, because you wanna consider the exist of 'from'
				$result = $this->primaryIndicatorResultGamma( $message, $result );
			}
		} else if ($result['numOfFrom'] == 0 && $result['numOfTo'] > 0){
			// Missing 'from'
			$result = $this->primaryIndicatorResultBeta($message, $result);
		} else if ($result['numOfFrom'] == 0 && $result['numOfTo'] == 0){
			// Missing both 'from' & 'to'
			if ( $result['numOfArrow'] == 0 ){
				// Try to match with location keywords dictionary
				// no keyword indicators found
			} else if ($result['numOfArrow'] > 0) {
				$result = $this->primaryIndicatorResultGamma( $message, $result );
			}
		}
		return $result;
	}

	protected function getMinElementIndex( $arrayValue ){
	    return $this->indexOf($arrayValue, min($arrayValue));
	}

	protected function cleanUpMessage( $message ){
		$message = strtolower($message);
		
		// Clean punctuation
		$message = preg_replace("/(\r\n|\n|\r)/", " ", $message);
		$message = preg_replace("/(\:|\;|\#|\.|\,|\!)/", "", $message);
		$message = preg_replace("/\s+/", " ", $message);
		
		// String split spaces
		$message = explode(" ", $message);
		return $message;
	}

	// Primary indicators include 'from', 'to', and '>'
	protected function findPrimaryIndicators( $message ){
		$result = $this->findPrimaryIndicatorsResult();
		foreach ($message as $index => $element) { 
			if ($element == 'from'){
				$result['numOfFrom']++;
				$result['indexOfFrom'][] = $index;
			} else if ($element == 'to'){
				//What if they are saying from DateTime to DateTime?
				$result['numOfTo']++;
				$result['indexOfTo'][] = $index;
			} else if ($this->stringContains($element, '>')) {
				$result['numOfArrow']++;
				$result['indexOfArrow'][] = $index;
			}
		}
		return $result;
	}

	// Primary indicators find $result model
	protected function findPrimaryIndicatorsResult(){
		return array(
			"numOfFrom" => 0,
			"indexOfFrom" => array(),
			
			"numOfTo" => 0,
			"indexOfTo" => array(),
			
			"numOfArrow" => 0,
			"indexOfArrow" => array(),

			"origin" => null,
			"destination" => null
		);
	}

	// Alpha Case
	protected function primaryIndicatorResultAlpha( $message, $result ){
		// Case 1
		if ( $result['numOfFrom'] == 1 && $result['numOfTo'] == 1 ){
			$result = $this->primaryIndicatorResultAlpha_Case1( $message, $result );
		} else if ( $result['numOfFrom'] > 1 && $result['numOfTo'] == 1){
			// Case 2: ignore the ones that are far away from 'to'
			$distanceBetweenFromAndTo = array();
			for( $i = 0; $i < count($result['indexOfFrom']); $i++ ){
				$distanceBetweenFromAndTo[$i] = abs($result['indexOfTo'][0] - $result['indexOfFrom'][$i]); 
			}
			// Set the 'from' that's most nearby 'to' to index location 0
			$result['indexOfFrom'][0] = $result['indexOfFrom'][$this->getMinElementIndex($distanceBetweenFromAndTo)];
			$result = $this->primaryIndicatorResultAlpha_Case1( $message, $result );
		} else if ( $result['numOfFrom'] == 1 && $result['numOfTo'] > 1 ){
			// Case 3: ignore the ones that are far away from 'from'
			$distanceBetweenFromAndTo = array();
			for( $i = 0; $i < count($result['indexOfTo']); $i++ ){
				$distanceBetweenFromAndTo[$i] = abs($result['indexOfFrom'][0] - $result['indexOfTo'][$i]); 
			}
			// Set the 'from' that's most nearby 'to' to index location 0
			$result['indexOfTo'][0] = $result['indexOfTo'][$this->getMinElementIndex($distanceBetweenFromAndTo)];
			$result = $this->primaryIndicatorResultAlpha_Case1( $message, $result );
		} else if ( $result['numOfFrom'] > 1 && $result['numOfTo'] > 1){
			// Case 4: where you might have multiple origin and destination
		 	// Multiple 'from' and 'to' appeared in the context
		}
		return $result;
	}

	protected function primaryIndicatorResultAlpha_Case1( $message, $result ){

		$pointerStart;
		$pointerEnd;
		$fromisBeforeTo;

		if ( $result['indexOfTo'][0] > $result['indexOfFrom'][0]){
			$pointerStart = $result['indexOfFrom'][0] + 1;
			$pointerEnd = $result['indexOfTo'][0];
			$fromisBeforeTo =  true;
		} else if ( $result['indexOfTo'][0] < $result['indexOfFrom'][0]) {
			$pointerStart = $result['indexOfTo'][0] + 1;
			$pointerEnd = $result['indexOfFrom'][0];
			$fromisBeforeTo = false;
		} else{
			return $result;
		}

		$firstLocation;
		$secondLocation;

		switch( $pointerEnd - $pointerStart ) {
			case 0:
				return $result;
			case 1:
				$firstLocation = $message[$pointerStart];
				$secondLocation = $message[$pointerEnd + 1];
				break;
			case 2:
				$firstLocation = $message[$pointerStart] . " " . $message[$pointerStart + 1];
				$secondLocation = $message[$pointerEnd + 1];
				break;
			case 3:
				if ( $this->stringContains($message[$pointerStart+1], '(') || $this->stringContains($message[$pointerStart+2], '(') ){
					$firstLocation = $message[$pointerStart] . " " . $message[$pointerStart+1] . " " . $message[$pointerStart+2];
				} else {
					$firstLocation = $message[$pointerStart];
				}
				$secondLocation = $message[$pointerEnd + 1];
				break;
			default:
				if ( $this->stringContains($message[$pointerStart+1], '(') && $this->stringContains($message[$pointerStart+2], ')') ){
					$firstLocation = $message[$pointerStart] . " " . $message[$pointerStart+1] . " " . $message[$pointerStart+2];
				} else if ( $this->stringContains($message[$pointerStart+1], '(') && !$this->stringContains($message[$pointerStart+2], ')') ){
					$firstLocation = $message[$pointerStart] . " " . $message[$pointerStart+1];
				} else {
					$firstLocation = $message[$pointerStart];
				}
				$secondLocation = $message[$pointerEnd + 1];
				break;
		}

		if ($fromisBeforeTo){
			$result['origin'] = $firstLocation;
			$result['destination'] = $secondLocation;
		} else {
			$result['destination'] = $firstLocation;
			$result['origin'] = $secondLocation;
		}
		return $result;
	}


	/*
	 * Beta Case
	 */
	protected function primaryIndicatorResultBeta( $message, $result ){
		//Case 1
		if ( $result['numOfTo'] == 1 ){
			$result = $this->primaryIndicatorResultBeta_Case1( $message, $result );
		} else if ( $result['numOfTo'] > 1){
			//Case 2: where you might have multiple origin and destination
			// Multiple 'to' appeared in the context")
		}
		return $result;
	}

	protected function primaryIndicatorResultBeta_Case1( $message, $result ){

		$pointer = $result['indexOfTo'][0];
		//If the string before 'to' contains keywords in dictionary, put origin to UW
		$dictionary = [ "drive", "driving", "leave", "leaving" ];
		$isOriginInContext = true;
		for ($i = 0; $i < count($dictionary); $i++){
			if ( $this->stringContains($message[$pointer-1], $dictionary[$i]) ) {
				$isOriginInContext = false;
			}
		}

		if ( $isOriginInContext  ){
			if ( $this->stringContains($message[$pointer-1], ')') ){
				$result['origin'] = $message[$pointer-2] . " " . $message[$pointer-1];
			} else {
				$result['origin'] = $message[$pointer-1];
			}

			if (isset($message[$pointer+2])) {
				if ( $this->stringContains($message[$pointer+2], '(') ) {
					$result['destination'] = $message[$pointer+1] . " " . $message[$pointer+2];
				} else{
					$result['destination'] = $message[$pointer+1];
				}
			} else if (isset($message[$pointer+1])) {
				$result['destination'] = $message[$pointer+1];
			}
		} else{
			$result['origin'] = "UWaterloo";
			if (isset($message[$pointer+1])) {
				$result['destination'] = $message[$pointer+1];
			}
		}
		return $result;
	}


	// Gamma Case
	protected function primaryIndicatorResultGamma( $message, $result ){
		//Case 1
		if ( $result['numOfArrow'] == 1 ){
			$result = $this->primaryIndicatorResultGamma_Case1( $message, $result );
		} else if ( $result['numOfArrow'] > 1 ){
			//Case 2 where you might have multiple origin and destination
			//Multiple '>' appeared in the context
		}
		return $result;
	}

	protected function primaryIndicatorResultGamma_Case1( $message, $result ) {
		$pointer = $result['indexOfArrow'][0];
		if (isset($message[$pointer-2]) && isset($message[$pointer-1])) {
			if ( $this->stringContains($message[$pointer-1], ')') ){
				$result['origin'] = $message[$pointer-2] . " " . $message[$pointer-1];
			} else {
				$result['origin'] = $message[$pointer-1];
			}
		}

		if (isset($message[$pointer+2]) && isset($message[$pointer+1])) {
			if ( $this->stringContains($message[$pointer+2], '(') ) {
				$result['destination'] = $message[$pointer+1] . " " . $message[$pointer+2];
			} else {
				$result['destination'] = $message[$pointer+1];
			}
		}

		return $result;
	}
}