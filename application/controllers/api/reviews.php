<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Reviews extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        $this->load->model('review');
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
    }

    public function index_get() {
        $receiver_id = $this->input->get('receiver_id');

        $reviews = $this->review->retrieve(
                array(
                    'receiver_id' => $receiver_id
                )
            );

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

                        if ( $fb_response ) {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Review successfully posted. Receiver notified on Facebook.',
                                    'review_id' => $review_id
                                )
                            );
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Review successfully posted. Receiver could not be notified on Facebook.',
                                    'review_id' => $review_id
                                )
                            );   
                        }                        
                    } else {
                        echo json_encode(
                            array(
                                'status' => 'success',
                                'message' => 'Review successfully posted. Receiver already notified on Facebook.',
                                'review_id' => $review_id
                            )
                        );
                    }
                } else {
                    echo json_encode( 
                        array(
                            'status'  => 'fail',
                            'message' => 'Self reviews not allowed'
                        )
                    );
                }
            } else {
                echo json_encode( 
                    array(
                        'status'  => 'fail',
                        'message' => 'Required information is missing'
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

    public function index_delete( $review_id = '' ) {
        if ( $this->session->userdata('user_id') ) {            
            $reviewer_id = $this->session->userdata('user_id');
            
            if ( $this->_verify_reviewer( $review_id, $reviewer_id) ) {
                $this->review->delete(
                    array(
                        'id' => $review_id
                    )
                );

                echo json_encode(
                    array(
                        'status' => 'success',
                        'message' => "Review successfully deleted."
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'You are not the reviewer. Stop hacking.'
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