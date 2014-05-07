<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Riderequests extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
    }

    public function index_post() {
        if ( $this->session->userdata('user_id') ) {            
            $data = $this->post();

            $origin = isset($data['origin']) ? $data['origin'] : '';
            $destination = isset($data['destination']) ? $data['destination'] : '';
            $departure_date = isset($data['departureDate']) ? $data['departureDate'] : '';
            $departure_time = isset($data['departureTime']) ? $data['departureTime'] : '';
            
            $start = strtotime( $departure_date . ' ' . $departure_time );
            $start = date('Y-m-d H:i:s', $start);

            $riderequest_id = $this->riderequest->create(  
                array(  
                    'user_id' => $this->session->userdata('user_id'),
                    'origin' => $origin,
                    'destination' => $destination,
                    'start' => $start  
                )
            );
            $riderequest = $this->riderequest->retrieve_by_id( $riderequest_id );
            
            echo json_encode(
                array(
                    'status' => 'success',
                    'message' => 'Request successfully posted.',
                    'riderequest' => $riderequest
                )
            );
    
        } else {
            echo json_encode(
                array(
                    'status' => 'fail',
                    'message' => 'User is not logged in.'
                )
            );
        }
        
        return;
    }

    public function index_delete( $riderequest_id = '' ) {
        if ( $this->session->userdata('user_id') ) {            
            $user_id = $this->session->userdata('user_id');
            
            if ( $this->_verify_user( $riderequest_id, $user_id) ) {
                $this->riderequest->delete(
                    array(
                        'id' => $riderequest_id
                    )
                );

                echo json_encode(
                    array(
                        'status' => 'success',
                        'message' => 'Request successfully deleted.'
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'You are not the requester. Stop hacking.'
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'status' => 'fail',
                    'message' => 'User is not logged in.'
                )
            );
        }

        return;
    }

    private function _verify_user( $riderequest_id, $user_id ) {
        $riderequest = $this->riderequest->retrieve_by_id( $riderequest_id );

        if ( $riderequest ) {
            if ( $riderequest->user_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }
}