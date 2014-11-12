<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Comments extends API_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }

    public function index_post() {
        if ( $this->wheelzo_user_id ) {            
            $data = clean_input( $this->post() );

            $ride_id = isset($data['rideID']) ? $data['rideID'] : '';
            $comment = isset($data['comment']) ? $data['comment'] : '';

            $ride = $this->ride->retrieve_by_id( $ride_id );
            
            if ( $ride ) {
                $comment_id = $this->comment->create(  
                    array(  
                        'user_id' => $this->wheelzo_user_id,
                        'ride_id' => $ride_id,
                        'comment' => $comment,
                        'last_updated' => date( 'Y-m-d H:i:s' )
                    )
                );

                $comment = $this->comment->retrieve_by_id( $comment_id );

                if ( $ride->driver_id != $comment->user_id ) { // commenter is not driver
                    $driver = $this->user->retrieve_by_id( $ride->driver_id );
                    $commenter = $this->user->retrieve_by_id( $comment->user_id );

                    $notification_type = $ride->id . NOTIFY_COMMENT; 
                    $to_notify = $this->user->to_notify( $driver->id, $notification_type );

                    if ( $to_notify ) {
                        $fb_response = false;
                        if ( ENVIRONMENT == 'production' || in_array($driver->facebook_id, unserialize(WHEELZO_ADMINS)) ) {
                            try {
                                $fb_response = $this->facebook->api(
                                    '/' . $driver->facebook_id . '/notifications', 
                                    'POST', 
                                    array(
                                        'href' => '/fb?goto='.$ride->id,
                                        'template' => '@[' . $commenter->facebook_id . '] commented on your ride scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
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
                            echo $this->message("Comment successfully posted. Driver notified on Facebook.");
                        } else {
                            http_response_code("200");
                            header('Content-Type: application/json');
                            echo $this->message("Comment successfully posted. Driver could not be notified on Facebook.");
                        }                        
                    } else {
                        http_response_code("200");
                        header('Content-Type: application/json');
                        echo $this->message("Comment successfully posted. Driver already notified on Facebook.");
                    }
                } else {
                    http_response_code("200");
                    header('Content-Type: application/json');
                    echo $this->message("Comment successfully posted");      
                }   
            } else {
                http_response_code("400");
                header('Content-Type: application/json');
                echo $this->message("Ride does not exist");
            }
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User not logged in");
        }
        
        return;
    }
}
