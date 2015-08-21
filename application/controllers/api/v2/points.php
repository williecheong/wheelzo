<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Points extends API_Controller {
    
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

    // Used to create a new group in the DB
    public function index_post() {
        $data = clean_input( $this->post() );
        
        if ( !$this->wheelzo_user_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
            return;
        }
            
        if ( !isset($data['receiver_id']) ){
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Receiver ID is missing");
            return;
        }

        if ( $this->wheelzo_user_id == $data['receiver_id'] ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Self scoring not allowed");
            return;
        }

        if ( $this->given_today($this->wheelzo_user_id, $data['receiver_id']) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("You may upvote each person once a day. Come again tomorrow.");
            return;
        }

        $receiver = $this->user->retrieve_by_id( $data['receiver_id'] );
        if ( !$receiver ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Invalid receiver ID");
            return;
        }        
        
        $point_id = $this->point->create(
            array(
                'giver_id' => $this->wheelzo_user_id,
                'receiver_id' => $data['receiver_id'],
                'last_updated' => date( 'Y-m-d H:i:s' )
            )
        );

        $this->user->update_rating( $point_id );

        // Facebook notify for point received
        $giver = $this->user->retrieve_by_id( $this->wheelzo_user_id );

        $notification_type = $giver->id . NOTIFY_VOUCHED; 
        $to_notify = $this->user->to_notify( $receiver->id, $notification_type );

        if ( !$to_notify ) {
            http_response_code("200");
            header('Content-Type: application/json');
            echo $this->message("Point successfully posted. Receiver already notified on Facebook.");
            return;
        }

        $fb_response = false;
        
        if ( ENVIRONMENT == 'production' || in_array($receiver->facebook_id, $GLOBALS['WHEELZO_TECH']) ) {
            try {
                $fb_response = $this->facebook->api(
                    '/' . $receiver->facebook_id . '/notifications', 
                    'POST', 
                    array(
                        'href' => '/fb?lookup='.$giver->id,
                        'template' => '@[' . $giver->facebook_id . '] has vouched for you.',
                        'access_token' => FB_APPID . '|' . FB_SECRET
                    )
                );
            } catch ( Exception $e ) {
                log_message('error', $e->getMessage() );
            }
        }
        
        if ( !$fb_response ) {
            http_response_code("200");
            header('Content-Type: application/json');
            echo $this->message("Point successfully posted. Receiver could not be notified on Facebook.");
            return;
        }
    
        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Point successfully posted. Receiver notified on Facebook.");
        return;
    }

    private function given_today( $giver_id = 0, $receiver_id = 0 ){
        $points = $this->point->retrieve(
                array(
                    'giver_id' => $giver_id,
                    'receiver_id' => $receiver_id,
                    'last_updated >' => date('Y-m-d H:i:s', strtotime('today midnight'))
                )
            );

        if ( count($points) > 0 ) {
            return true;
        } else {
            return false;
        }
    }
}