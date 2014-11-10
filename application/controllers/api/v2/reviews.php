<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Reviews extends API_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        $this->load->model('review');
    }

    public function index_get() {
        $receiver_id = $this->input->get('receiver_id');

        $reviews = $this->review->retrieve(
                array(
                    'receiver_id' => $receiver_id
                )
            );

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($reviews);
        return;
    }

    // Used to create a new group in the DB
    public function index_post() {
        $data = clean_input( $this->post() );
        
        if ( $this->session->userdata('user_id') ) {
            
            if ( isset($data['receiver_id']) && isset($data['review']) ){
                
                if ( $this->session->userdata('user_id') != $data['receiver_id'] ) {
                    $review_id = $this->review->create(
                        array(
                            'giver_id' => $this->session->userdata('user_id'),
                            'receiver_id' => $data['receiver_id'],
                            'review' => $data['review']
                        )
                    );

                    // Facebook notify for review received
                    $receiver = $this->user->retrieve_by_id( $data['receiver_id'] );
                    $giver = $this->user->retrieve_by_id( $this->session->userdata['user_id'] );

                    $notification_type = $giver->id . NOTIFY_REVIEWED; 
                    $to_notify = $this->user->to_notify( $receiver->id, $notification_type );

                    if ( $to_notify ) {
                        $fb_response = false;
                        if ( ENVIRONMENT == 'production' || in_array($receiver->facebook_id, unserialize(WHEELZO_ADMINS)) ) {
                            try {
                                $fb_response = $this->facebook->api(
                                    '/' . $receiver->facebook_id . '/notifications', 
                                    'POST', 
                                    array(
                                        'href' => '/fb?lookup='.$receiver->id,
                                        'template' => '@[' . $giver->facebook_id . '] has written a review for you.',
                                        'access_token' => FB_APPID . '|' . FB_SECRET
                                    )
                                );
                            } catch ( Exception $e ) {
                                log_message('error', $e->getMessage() );
                            }
                        }

                        if ( $fb_response ) {
                            http_response_code("200");
                            header('Content-Type: application/json');
                            echo $this->message("Review successfully posted. Receiver notified on Facebook.");
                        } else {
                            http_response_code("200");
                            header('Content-Type: application/json');
                            echo $this->message("Review successfully posted. Receiver could not be notified on Facebook.");
                        }                        
                    } else {
                        http_response_code("200");
                        header('Content-Type: application/json');
                        echo $this->message("Review successfully posted. Receiver already notified on Facebook.");
                    }
                } else {
                    http_response_code("400");
                    header('Content-Type: application/json');
                    echo $this->message("Self reviews not allowed");
                }
            } else {
                http_response_code("400");
                header('Content-Type: application/json');
                echo $this->message("Required information is missing");
            }
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
        }

        return;
    }

    public function index_delete( $review_id = '' ) {
        if ( $this->session->userdata('user_id') ) {            
            $reviewer_id = $this->session->userdata('user_id');
            
            if ( $this->_verify_reviewer( $review_id, $reviewer_id) ) {
                $this->review->delete(
                    array(
                        'id' => $review_id
                    )
                );

                http_response_code("200");
                header('Content-Type: application/json');
                echo $this->message("Review successfully deleted");
            } else {
                http_response_code("400");
                header('Content-Type: application/json');
                echo $this->message("You are not the reviewer. Stop hacking.");
            }
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
        }
    }

    private function _verify_reviewer( $review_id, $user_id ) {
        $review = $this->review->retrieve_by_id( $review_id );

        if ( $review ) {
            if ( $review->giver_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }
}