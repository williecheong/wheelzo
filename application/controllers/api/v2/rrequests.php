<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Rrequests extends API_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }
    
    public function ping_get() {
        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("OK");
        return;
    }

    public function index_post() {
        if ( !$this->wheelzo_user_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
            return;
        }

        $data = clean_input( $this->post() );

        $origin = isset($data['origin']) ? $data['origin'] : '';
        $destination = isset($data['destination']) ? $data['destination'] : '';
        $departure_date = isset($data['departureDate']) ? $data['departureDate'] : '';
        $departure_time = isset($data['departureTime']) ? $data['departureTime'] : '';
        
        $start = strtotime( $departure_date . ' ' . $departure_time );
        $start = date('Y-m-d H:i:s', $start);

        $rrequest_id = $this->rrequest->create(  
            array(  
                'user_id' => $this->wheelzo_user_id,
                'origin' => $origin,
                'destination' => $destination,
                'start' => $start  
            )
        );
        $rrequest = $this->rrequest->retrieve_by_id( $rrequest_id );
        
        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Request successfully posted");
        return;
    }

    public function index_delete( $rrequest_id = '' ) {
        if ( !$this->wheelzo_user_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
            return;
        }
        
        $user_id = $this->wheelzo_user_id;
        
        if ( !$this->_verify_user( $rrequest_id, $user_id) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("You are not the requester. Stop hacking.");
        }
        $this->rrequest->delete(
            array(
                'id' => $rrequest_id
            )
        );

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Request successfully deleted");
        return;
    }

    private function _verify_user( $rrequest_id, $user_id ) {
        $rrequest = $this->rrequest->retrieve_by_id( $rrequest_id );

        if ( $rrequest ) {
            if ( $rrequest->user_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }
}