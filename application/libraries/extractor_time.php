<?php

class ExtractorTime extends ExtractorBase {

	public function getTime($message, $postTime) {
		$originalMessage = $message;
	    $message = $this->extractDeparture_cleanText($message);
	    $userDescription = null;

	    /* Setting the date of $departure */
	    // dealing with the straightforward cases first
	    if ( is_null($userDescription) ) {
	        if ( $this->stringContains($message, "today") || $this->stringContains($message, "tonight") ) {
	            $userDescription = "now";
	        } else if ( $this->stringContains($message, "tomorrow") ) {
	            $userDescription = "+1 day";
	        }
	    }
	    if ( is_null($userDescription) ) {
	        $dateHintExpressions = array(
	            "next monday",
	            "next tuesday",
	            "next wednesday",
	            "next thursday",
	            "next friday",
	            "next saturday",
	            "next sunday"
	        );
	        for ($i=0; $i<count($dateHintExpressions); $i++) {
	            if ( $this->stringContains($message, $dateHintExpressions[$i]) ) {
	                $userDescription = $dateHintExpressions[$i];
	            }
	        }
	    };

	    if ( is_null($userDescription) ) {
	        $userDescription = "now";
	    }
	    /* End of setting the date of $departure */

	    /* Setting the time of $departure */
	    $hour = 12;
	    $minute = 0;
	    $second = 0;
	    if ( $this->stringContains($message, "morning") ) {
	        $hour = 10;
	    } else if ( $this->stringContains($message, "noon") ) {
	        if ( $this->stringContains($message, "afternoon") ) {
	            $hour = 15;
	        } else {
	            $hour = 12;
	        }
	    } else if ( $this->stringContains($message, "tonight") || $this->stringContains($message, "evening") ) {
	        $hour = 20;
	    } else if ( $this->stringContains($message, "@catchTime") ) {
	        $timestampIdentifier = 0;
	        $words = explode(" ", $message);

	        if ( $this->stringContains($message, "am@catchTime") ) {
	            $hour = 0;
	            $timestampIdentifier = $this->indexOf($words, "am@catchTime");

	        } else {
	            $timestampIdentifier = $this->indexOf($words, "pm@catchTime");
	        }

	        $hourLocation = $timestampIdentifier;
	        while ( is_numeric($words[$hourLocation-1]) ) {
	            $hourLocation = $hourLocation - 1;
	            if ($hourLocation < 0) {
	                break;
	            }
	        }

	        if ( isset($words[$hourLocation]) ) {
	          $hour = $hour + intval($words[$hourLocation]);
	        } else {
	          $hour = 0;
	        }

	        if ( isset($words[$hourLocation+1]) ) {
	            if ( is_numeric($words[$hourLocation+1]) ) {
	                $minute = $minute + intval( $words[$hourLocation+1] );
	            }
	        }
	    } else {
	        // cannot find anything, use preset defaults
	    }
	    /* End of setting the date of departure */

	    // Building out the date object to return
		$date = new DateTime();
		$date->setTimestamp(
			strtotime(
				$userDescription, 
				strtotime($postTime)
			)
		);
	    $date = $date->format('Y-m-d');

	    $departure = strtotime($date." ".$hour.":".$minute.":".$second);

	    //return date('Y-m-d H:i:s', $departure); // mysql format
	    return gmdate('Y-m-d\TH:i:s\Z', $departure); // iso format
	}

	protected function extractDeparture_cleanText( $message ) {
		$message = preg_replace("/(\r\n|\n|\r)/", " ", $message);
		$message = preg_replace("/(\(|\)|\:|\;|\#|\/|\.|\,|\!|\-|\~)/", " ", $message);
		$message = strtolower($message);

		$message = preg_replace("/ mon /", " monday ", $message);
		$message = preg_replace("/ tue /", " tuesday ", $message);
		$message = preg_replace("/ tues /", " tuesday ", $message);
		$message = preg_replace("/ wed /", " wednesday ", $message);
		$message = preg_replace("/ thu /", " thursday ", $message);
		$message = preg_replace("/ thur /", " thursday ", $message);
		$message = preg_replace("/ thurs /", " thursday ", $message);
		$message = preg_replace("/ fri /", " friday ", $message);
		$message = preg_replace("/ sat /", " saturday ", $message);
		$message = preg_replace("/ sun /", " sunday ", $message);

		$message = preg_replace("/monday/", "next monday", $message);
		$message = preg_replace("/tuesday/", "next tuesday", $message);
		$message = preg_replace("/wednesday/", "next wednesday", $message);
		$message = preg_replace("/thursday/", "next thursday", $message);
		$message = preg_replace("/friday/", "next friday", $message);
		$message = preg_replace("/saturday/", "next saturday", $message);
		$message = preg_replace("/sunday/", "next sunday", $message);

		$message = preg_replace("/(\dam)/", "$1@catchTime ", $message);
		$message = preg_replace("/(\dpm)/", "$1@catchTime ", $message);
		$message = preg_replace("/(\sam)/", "$1@catchTime ", $message);
		$message = preg_replace("/(\spm)/", "$1@catchTime ", $message);
		$message = preg_replace("/am@catchTime/", " am@catchTime ", $message);
		$message = preg_replace("/pm@catchTime/", " pm@catchTime ", $message);

		$message = preg_replace("/\s+/", " ", $message);

		return $message;                
	}
}