<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Comments extends REST_Controller {
    
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

            $ride_id = isset($data['rideID']) ? $data['rideID'] : '';
            $comment = isset($data['comment']) ? $data['comment'] : '';

            $ride = $this->ride->retrieve_by_id( $ride_id );
            
            if ( $ride ) {
                $comment_id = $this->comment->create(  
                    array(  
                        'user_id' => $this->session->userdata('user_id'),
                        'ride_id' => $ride_id,
                        'comment' => $comment
                    )
                );

                $comment = $this->comment->retrieve_by_id( $comment_id );

                if ( $ride->driver_id != $comment->user_id ) { // commenter is not driver
                    $driver = $this->user->retrieve_by_id( $ride->driver_id );
                    $commenter = $this->user->retrieve_by_id( $comment->user_id );

                    $notification_type = $ride->id . 'C'; 
                    $to_notify = $this->user->to_notify( $driver->id, $notification_type );

                    if ( $to_notify ) {
                        $fb_response = false;
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

                        if ( $fb_response ) {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Comment successfully posted. Driver notified on Facebook.',
                                    'comment' => $comment
                                )
                            );
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Comment successfully posted. Driver could not be notified on Facebook.',
                                    'comment' => $comment
                                )
                            );   
                        }                        
                    } else {
                        echo json_encode(
                            array(
                                'status' => 'success',
                                'message' => 'Comment successfully posted. Driver already notified on Facebook.',
                                'comment' => $comment
                            )
                        );
                    }
                } else {
                    echo json_encode(
                        array(
                            'status' => 'success',
                            'message' => 'Comment successfully posted.',
                            'comment' => $comment
                        )
                    );            
                }   
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'Ride does not exist.'
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
}
