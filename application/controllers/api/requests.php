<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Requests extends REST_Controller {
    
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

    public function index_get() {
        $requests = $this->request->retrieve_active();
        echo json_encode($requests);
        return;
    }

    public function me_get() {
        $requests = $this->request->retrieve_personal();
        echo json_encode($requests);
        return;
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

            $request_id = $this->request->create(  
                array(  
                    'user_id' => $this->session->userdata('user_id'),
                    'origin' => $origin,
                    'destination' => $destination,
                    'start' => $start  
                )
            );
            $request = $this->request->retrieve_by_id( $request_id );
            
            echo json_encode(
                array(
                    'status' => 'success',
                    'message' => 'Request successfully posted.',
                    'request' => $request
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

    public function index_delete( $request_id = '' ) {
        if ( $this->session->userdata('user_id') ) {            
            $user_id = $this->session->userdata('user_id');
            
            if ( $this->_verify_user( $request_id, $user_id) ) {
                $this->request->delete(
                    array(
                        'id' => $request_id
                    )
                );

                echo json_encode(
                    array(
                        'status' => 'success',
                        'message' => "Request successfully deleted."
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
    }

    private function _verify_user( $request_id, $user_id ) {
        $request = $this->request->retrieve_by_id( $request_id );

        if ( $request ) {
            if ( $request->user_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }
}