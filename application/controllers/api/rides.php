<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Rides extends REST_Controller {
    
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

    public function index_get() {
        $rides = $this->ride->retrieve_active();
        echo json_encode($rides);
        return;
    }

    public function me_get() {
        $rides = $this->ride->retrieve_personal();
        echo json_encode($rides);
        return;
    }    

    public function index_post() {
        if ( $this->session->userdata('user_id') ) {            
            $data = clean_input( $this->post() );

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

    public function index_delete( $ride_id = '' ) {
        if ( $this->session->userdata('user_id') ) {            
            $driver_id = $this->session->userdata('user_id');
            
            if ( $this->_verify_driver( $ride_id, $driver_id) ) {
                // Handle the deleting of all comments
                $this->comment->delete(
                    array(
                        'ride_id' => $ride_id
                    )
                );

                // Handle the deleting of all assignments
                // Use user_rides API to notify passengers
                $user_rides = $this->user_ride->retrieve(
                    array(
                        'ride_id' => $ride_id
                    )
                );

                $output_message = '';

                foreach( $user_rides as $user_ride ) {
                    $response = array();
                    $old_user_ride = $this->user_ride->retrieve_by_id( $user_ride->id );
                
                    $this->user_ride->delete( 
                        array( 
                            'id' => $user_ride->id
                        )
                    );

                    $ride = $this->ride->retrieve_by_id( $old_user_ride->ride_id );
                    
                    if ( $old_user_ride->user_id != $driver_id ) {
                        $driver = $this->user->retrieve_by_id( $ride->driver_id );
                        $old_passenger = $this->user->retrieve_by_id( $old_user_ride->user_id );
                        
                        $notification_type = $ride->id . 'D';
                        $to_notify = $this->user->to_notify( $old_passenger->id, $notification_type );
                        
                        if ( $to_notify ) {        
                            $fb_response_to_old = false;
                            try {
                                $fb_response_to_old = $this->facebook->api(
                                    '/' . $old_passenger->facebook_id . '/notifications', 
                                    'POST', 
                                    array(
                                        'href' => '/fb?goto='.$ride->id, 
                                        'template' => '@[' . $driver->facebook_id . '] cancelled a ride you were in that was scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
                                        'access_token' => FB_APPID . '|' . FB_SECRET
                                    )
                                );
                            } catch ( Exception $e ) {
                                log_message('error', $e->getMessage() );
                            }
                            
                            if ( $fb_response_to_old ) {
                                $output_message .= $old_passenger->name." successfully removed and notified on Facebook.\n";
                            } else {
                                $output_message .= $old_passenger->name." successfully removed but could not be notified on Facebook.\n";
                            }
                        } else {
                            $output_message .= $old_passenger->name." successfully removed and already notified on Facebook.\n";
                        }
                    } else {
                        $output_message .= "You were successfully removed from your own ride.\n";
                    }
                }

                // Finally, delete the ride
                $this->ride->delete(
                    array(
                        'id' => $ride_id
                    )
                );

                $output_message .= "Ride successfully deleted.";

                echo json_encode(
                    array(
                        'status' => 'success',
                        'message' => $output_message
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'You are not the driver. Stop hacking.'
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

    private function _verify_driver( $ride_id, $user_id ) {
        $ride = $this->ride->retrieve_by_id( $ride_id );

        if ( $ride ) {
            if ( $ride->driver_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }
}