<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Test extends API_Controller {
    
    function __construct() {
        parent::__construct();
        if ( in_array($this->wheelzo_facebook_id, $GLOBALS['WHEELZO_BDEV']) ) {
            $this->load->model('facebook_ride');
        } else {
            redirect( base_url() );            
        }
    }
    
    public function ping_get() {
        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("OK");
        return;
    }

    public function extractor_get() { // used to test extractor
        $this->load->library('extractor');
        $test_cases = $this->mock_facebook_postings();

        foreach ($test_cases as $key => $test_case) {
            $test_cases[$key]['ride'] = $this->extractor->getRideFromMessage($test_case['message'], $test_case['timestamp']);
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($test_cases);
        return;

    }

    private function mock_facebook_postings() {
        return array(
            array(
                "message" => "Driving Saturday Toronto (Fairview) -> Waterloo 7pm $15 Posting for friend, please text (519) 721-5893", 
                "timestamp" => "2014-10-28T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Mississauga to Waterloo tomorrow (Saturday) at 4:30pm 3 spots available, leaving from Mississauga Rd/Derry", 
                "timestamp" => "2014-10-31T04:13:59+0000"
            ),
            array(
                "message" => "Driving to Waterloo tonight around 8:30 pm from Etobicoke. Let me know if you need a ride.", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Brampton to Waterloo after 5pm. $28 a seat.", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving to MISSISSAUGA from UW Campus SATURDAY (Nov 01) morning. Drop off: Square One or anywhre along 401. $10 per seat. Inbox if interested.", 
                "timestamp" => "2014-10-30T04:13:59+0000"
            ),
            array(
                "message" => "Driving from north York (downsview) to Waterloo on Saturday nov 1 in afternoon/evening, $10, message me", 
                "timestamp" => "2014-10-30T04:13:59+0000"
            ),
            array(
                "message" => "Mississauga -> waterloo 6pm today Inbox for details.", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Finch Station to Waterloo on Saturday, November 1 at 7pm.", 
                "timestamp" => "2014-10-30T04:13:59+0000"
            ),
            array(
                "message" => "Sunday 5:30pm Driving Waterloo > Mississauga Sq.1 $10/seat Inbox!", 
                "timestamp" => "2014-10-30T19:00:00+0000"
            ),
            array(
                "message" => "Driving to Toronto(Kipling station) from waterloo Tomorrow (Saturday) ~9am. 10$ a seat", 
                "timestamp" => "2014-10-31T19:00:00+0000"
            ),
            array(
                "message" => "Driving from Waterloo to DT today at 3pm $18/seat inbox me if interested", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Waterloo ---> Toronto Downtown Tomorrow (Friday) 1:30pm $17 3 seats available", 
                "timestamp" => "2014-10-30T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Richmon Hill/Vaughan to Waterloo Sunday at 8pm $12", 
                "timestamp" => "2014-10-31T04:13:59+0000"
            ),
            array(
                "message" => "Driving to Milton from Waterloo at 3:30 pm. Message if interested.", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Waterloo to DT toronto at 12.30 pm ( in about 40 mins). Two more seats available. $12/person", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving from Waterloo to Mississauga this afternoon/evening. Send me a text: 647 530 6553", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving from UW to Bayview/sheppard area. Friday at 6:30PM. Inbox if interested.", 
                "timestamp" => "2014-10-28T04:13:59+0000"
            ),
            array(
                "message" => "Driving Waterloo to Toronto (eatons/fairview) Saturday Nov 1st around 2-3pm, 2 seats Also Driving Toronto (markham area) to Waterloo Sunday Nov2nd around 10AM, 2 seats Inbox ASAP", 
                "timestamp" => "2014-10-29T04:13:59+0000"
            ),
            array(
                "message" => "Driving to Markham/Richmond Hill/North York (flexible drop off within reason) tonight at 10pm. $14 a seat, lots of room for luggage.", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Driving waterloo to mississauga (heartland). Leaving at 1:30-1:45. $19", 
                "timestamp" => "2014-11-03T04:13:59+0000"
            ),
            array(
                "message" => "Leaving waterloo between 1 to 2pm today 31st. I can drop u off either Richmond hill centre or finch! If u need a ride or details, plz pm me asap.", 
                "timestamp" => "2014-10-31T04:13:59+0000"
            ),
            array(
                "message" => "Driving Waterloo to Markham Thursday Nov 13 at 10 PM  $16/seat", 
                "timestamp" => "2014-11-10T03:01:54+0000"
            )
        );
    }
}