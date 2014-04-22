<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Rides extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }

    public function index_get() {
        $rides = $this->ride->retrieve_relevant();
        echo json_encode($rides);
        return;
    }

    public function index_post() {
        if ( $this->session->userdata('user_id') ) {            
            $data = $this->post();

            $origin = isset($data['origin']) ? $data['origin'] : '';
            $destination = isset($data['destination']) ? $data['destination'] : '';
            $departure_date = isset($data['departureDate']) ? $data['departureDate'] : '';
            $departure_time = isset($data['departureTime']) ? $data['departureTime'] : '';
            $capacity = isset($data['capacity']) ? $data['capacity'] : '1';
            $price = isset($data['price']) ? $data['price'] : '0';

            $start = strtotime( $departure_date . ' ' . $departure_time );
            $start = date('Y-m-d H:i:s', $start);

            $drop_offs = isset($data['dropOffs']) ? implode(WHEELZO_DELIMITER, $data['dropOffs']) : '';

            $ride_id = $this->ride->create(  
                array(  
                    'driver_id' => $this->session->userdata('user_id'),
                    'origin' => $origin,
                    'destination' => $destination,
                    'capacity' => $capacity,
                    'price' => $price,
                    'start' => $start,
                    'drop_offs' => $drop_offs   
                )
            );
            $ride = $this->ride->retrieve_by_id( $ride_id );
            
            echo json_encode(
                array(
                    'status' => 'success',
                    'message' => 'Ride successfully posted.',
                    'ride' => $ride
                )
            );
    
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