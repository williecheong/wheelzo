<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Comments extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }

    public function index_post() {
        if ( $this->session->userdata('user_id') ) {            
            $data = $this->post();

            $ride_id = isset($data['rideID']) ? $data['rideID'] : '';
            $comment = isset($data['comment']) ? $data['comment'] : '';
            
            $comment_id = $this->comment->create(  
                array(  
                    'user_id' => $this->session->userdata('user_id'),
                    'ride_id' => $ride_id,
                    'comment' => $comment
                )
            );

            $comment = $this->comment->retrieve(
                array(
                    'id' => $comment_id
                )
            );
            
            echo json_encode(
                array(
                    'status' => 'success',
                    'message' => 'Comment successfully posted.',
                    'ride' => $comment[0]
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
}