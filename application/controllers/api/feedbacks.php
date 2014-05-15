<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Feedbacks extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        $this->load->model('feedback');
    }

    // Used to create a new group in the DB
    public function index_post() {
        $data = clean_input( $this->post() );
        
        if ( isset($data['message']) ){
            $email = '';
            if ( isset($data['email']) ) {
                $email = $data['email'];
            } 

            $user_id = 0;
            if ( $this->session->userdata('user_id') ) {
                $user_id = $this->session->userdata('user_id');
            }

            $feedback_id = $this->feedback->create(
                array(
                    'email' => $email,
                    'user_id' => $user_id,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'message' => $data['message']
                )
            );

            echo json_encode(
                array(
                    'status' => 'success',
                    'message' => 'Feedback posted successful',
                    'feedback_id' => $feedback_id
                )
            );
            
        } else {
            echo json_encode( 
                array(
                    'status'  => 'fail',
                    'message' => 'Message is missing from feedback'
                )
            );
        }

        return;
    }
}