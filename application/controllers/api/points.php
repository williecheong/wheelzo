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
                
                    $point_id = $this->point->create(
                        array(
                            'giver_id' => $this->session->userdata('user_id'),
                            'receiver_id' => $data['receiver_id']
                        )
                    );

                    $this->user->update_rating( $point_id );

                    echo json_encode(
                        array(
                            'status' => 'success',
                            'message' => 'Point posted successful',
                            'point_id' => $point_id
                        )
                    );
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
}