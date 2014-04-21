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

            $rides = $this->ride->retrieve(
                array(
                    'id' => $ride_id
                )
            );
            
            if ( count($rides) > 0 ) {
                $ride = $rides[0];

                $comment_id = $this->comment->create(  
                    array(  
                        'user_id' => $this->session->userdata('user_id'),
                        'ride_id' => $ride_id,
                        'comment' => $comment
                    )
                );

                $comments = $this->comment->retrieve(
                    array(
                        'id' => $comment_id
                    )
                );

                $comment = $comments[0];

                if ( $ride->driver_id != $comment->user_id ) { // commenter is not driver
                    $driver = $this->user->retrieve(
                        array(
                            'id' => $ride->driver_id
                        )
                    );

                    $commenter = $this->user->retrieve(
                        array(
                            'id' => $comment->user_id
                        )
                    );

                    $comments_since_last_login = $this->comment->retrieve( 
                        array(
                            'ride_id' => $ride->id, 
                            'last_updated > ' => $driver[0]->last_updated 
                        )
                    );

                    if ( count($comments_since_last_login) == 0 ) {
                        $fb_response = false;
                        try {
                            $fb_response = $this->facebook->api(
                                '/' . $driver[0]->facebook_id . '/notifications', 
                                'POST', 
                                array(
                                    'href' => '/fb?goto='.$ride->id,
                                    'template' => '@[' . $commenter[0]->facebook_id . '] commented on your ride scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
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
