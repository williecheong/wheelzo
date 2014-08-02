<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Reviews extends REST_Controller {
    
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

                    echo json_encode(
                        array(
                            'status' => 'success',
                            'message' => 'Review posted successful',
                            'review_id' => $review_id
                        )
                    );
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
}