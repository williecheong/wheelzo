<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Points extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        $this->load->model('point');
    }

    // Used to create a new group in the DB
    public function index_post() {
        $data = clean_input( $this->post() );
        
        if ( $this->session->userdata('user_id') ) {
            
            if ( isset($data['receiver_id']) ){
                
                if ( $this->session->userdata('user_id') != $data['receiver_id'] ) {
                    
                    if ( !$this->given_today($this->session->userdata('user_id'), $data['receiver_id']) ) {
                        $point_id = $this->point->create(
                            array(
                                'giver_id' => $this->session->userdata('user_id'),
                                'receiver_id' => $data['receiver_id'],
                                'last_updated' => date( 'Y-m-d H:i:s' )
                            )
                        );

                        $this->user->update_rating( $point_id );

                        // Facebook notify for point received
                        $receiver = $this->user->retrieve_by_id( $data['receiver_id'] );
                        $giver = $this->user->retrieve_by_id( $this->session->userdata['user_id'] );

                        $notification_type = $giver->id . NOTIFY_VOUCHED; 
                        $to_notify = $this->user->to_notify( $receiver_id->id, $notification_type );

                        if ( $to_notify ) {
                            $fb_response = false;
                            try {
                                $fb_response = $this->facebook->api(
                                    '/' . $receiver->facebook_id . '/notifications', 
                                    'POST', 
                                    array(
                                        'href' => '/fb',
                                        'template' => '@[' . $giver->facebook_id . '] has vouched for you on Wheelzo.',
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
                                        'message' => 'Point successfully posted. Receiver notified on Facebook.',
                                        'point_id' => $point_id
                                    )
                                );
                            } else {
                                echo json_encode(
                                    array(
                                        'status' => 'success',
                                        'message' => 'Point successfully posted. Receiver could not be notified on Facebook.',
                                        'point_id' => $point_id
                                    )
                                );   
                            }                        
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Point successfully posted. Receiver already notified on Facebook.',
                                    'point_id' => $point_id
                                )
                            );
                        }
                    } else {
                        echo json_encode( 
                            array(
                                'status'  => 'fail',
                                'message' => "You may upvote each person once a day.\nCome again tomorrow."
                            )
                        );
                    }
                } else {
                    echo json_encode( 
                        array(
                            'status'  => 'fail',
                            'message' => 'Self scoring not allowed'
                        )
                    );
                }
            } else {
                echo json_encode( 
                    array(
                        'status'  => 'fail',
                        'message' => 'Receiver ID is missing'
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