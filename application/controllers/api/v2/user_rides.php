<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class User_rides extends API_Controller {
    
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

    public function index_get() {
        $user_rides = array();
        
        if ( $this->get('ride_id') ) {
            $user_rides = $this->user_ride->retrieve(
                array(
                    'ride_id' => $this->get('ride_id')
                )
            );
        } 

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($user_rides);
        return;
    }

    private function _verify_driver_by_user_ride( $user_ride_id, $user_id ) {
        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );

        if ( $user_ride ) {
            return $this->_verify_driver($user_ride->ride_id, $user_id);
        }

        return false;
    }

    private function _verify_passenger_by_user_ride( $user_ride_id, $user_id ) {
        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );

        if ( $user_ride ) {
            return $this->_verify_passenger($user_ride->ride_id, $user_id);
        }

        return false;
    }

    private function _verify_driver( $ride_id, $user_id ) {
        $ride = $this->ride->retrieve_by_id( $ride_id );

        if ( $ride ) {
            if ( $ride->driver_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }

    private function _verify_passenger( $ride_id, $user_id ) {
        // Only people who have commented before can be a passenger
        $comments = $this->comment->retrieve(
            array(
                'ride_id' => $ride_id
            )
        );

        foreach ( $comments as $comment ) {
            if ( $comment->user_id == $user_id ) {
                return true;
            }
        }

        return false;
    }

    private function _verify_capacity( $ride_id ) {
        $ride = $this->ride->retrieve_by_id( $ride_id );

        if ( $ride ) {
            $user_rides = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride_id
                )
            );

            if ( count($user_rides) < $ride->capacity ) {
                return true;
            }

        }

        return false;
    }
}
