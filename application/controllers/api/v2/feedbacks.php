<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Feedbacks extends API_Controller {
    
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
            if ( $this->wheelzo_user_id ) {
                $user_id = $this->wheelzo_user_id;
            }

            $feedback_id = $this->feedback->create(
                array(
                    'email' => $email,
                    'user_id' => $user_id,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'message' => $data['message']
                )
            );

            http_response_code("200");
            header('Content-Type: application/json');
            echo $this->message("Feedback posted successfully");
            
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Message is missing from feedback");
        }

        return;
    }
}