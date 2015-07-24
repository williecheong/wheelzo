<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Rides extends API_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }

    public function index_get( $load_personal = false ) {
        $rides = array();
        if ( $load_personal ) {
            $rides = $this->ride->retrieve_personal();
        } else {
            $rides = $this->ride->retrieve_active();
        }

        $mapped_rides = array();
        foreach( $rides as $key => $ride ) {
            $rides[$key]->comments = $this->comment->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );

            $rides[$key]->passengers = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );

            $mapped_rides[$ride->id] = $rides[$key];
        }

        echo json_encode($mapped_rides);
        return;
    }  

    public function index_post() {
        if ( $this->wheelzo_user_id ) {            
            $data = clean_input( $this->post() );

            $driver_id = $this->wheelzo_user_id;
            $origin = isset($data['origin']) ? $data['origin'] : '';
            $destination = isset($data['destination']) ? $data['destination'] : '';
            $departure_date = isset($data['departureDate']) ? $data['departureDate'] : '';
            $departure_time = isset($data['departureTime']) ? $data['departureTime'] : '';
            $capacity = isset($data['capacity']) ? $data['capacity'] : '1';
            $price = isset($data['price']) ? $data['price'] : '0';

            $start = strtotime( $departure_date . ' ' . $departure_time );
            $start = date('Y-m-d H:i:s', $start);

            $drop_offs = isset($data['dropOffs']) ? implode(WHEELZO_DELIMITER, $data['dropOffs']) : '';

            $allow_payments = isset($data['allowPayments']) ? $data['allowPayments'] : 0;
            if ($allow_payments != 0 && $allow_payments != 1) {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'Invalid payment option provided.'
                    )
                );
            }

            $ride_id = $this->ride->create(  
                array(  
                    'driver_id' => $driver_id,
                    'origin' => $origin,
                    'destination' => $destination,
                    'capacity' => $capacity,
                    'price' => $price,
                    'start' => $start,
                    'drop_offs' => $drop_offs,
                    'allow_payments' => $allow_payments
                )
            );
            $ride = $this->ride->retrieve_by_id( $ride_id );
            $output_message = "Ride successfully posted.";

            $driver = $this->user->retrieve_by_id( $driver_id );
            $invitees = isset($data['invitees']) ? $data['invitees'] : array();
            foreach( $invitees as $invitee ) {
                $rrequest = $this->rrequest->retrieve_by_id( $invitee );
                if ( $rrequest ) {
                    $passenger = $this->user->retrieve_by_id( $rrequest->user_id );
                    if ( $passenger ) {
                        $notification_type = $ride->id . NOTIFY_INVITED; 
                        $to_notify = $this->user->to_notify( $driver->id, $notification_type );

                        if ( $to_notify ) {
                            $fb_response = false;
                            if ( ENVIRONMENT == 'production' || in_array($passenger->facebook_id, $GLOBALS['WHEELZO_TECH']) ) {
                                try {
                                    $fb_response = $this->facebook->api(
                                        '/' . $passenger->facebook_id . '/notifications', 
                                        'POST', 
                                        array(
                                            'href' => '/fb?goto='.$ride->id,
                                            'template' => '@[' . $driver->facebook_id . '] invited you to a ride going from '.$ride->origin.' to '.$ride->destination.', scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
                                            'access_token' => FB_APPID . '|' . FB_SECRET
                                        )
                                    );
                                } catch ( Exception $e ) {
                                    log_message('error', $e->getMessage() );
                                }
                            }

                            if ( $fb_response ) {
                                $success = $this->rrequest->add_invitation( $invitee, $ride->id );
                                $output_message .= "\n" . $passenger->name . " successfully notified on Facebook about this invitation.";
                            } else {
                                $output_message .= "\n" . $passenger->name . " could not be notified on Facebook about this invitation.";
                            }
                        } else {
                            $output_message .= "\n" . $passenger->name . " was already notified on Facebook about this invitation.";
                        }
                    } else {
                        $output_message .= "\nPassenger of ride request not found. So weird.";
                    }
                } else {
                    $output_message .= "\nRide request not found. Stop hacking.";
                }
            }

            echo json_encode(
                array(
                    'status' => 'success',
                    'message' => $output_message,
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
        if ( $this->wheelzo_user_id ) {            
            $driver_id = $this->wheelzo_user_id;
            
            if ( $this->_verify_driver( $ride_id, $driver_id) ) {
                // Handle the deleting of all assignments
                // Use user_rides API to notify passengers
                $user_rides = $this->user_ride->retrieve(
                    array(
                        'ride_id' => $ride_id
                    )
                );

                if (count($user_rides) == 0) {
                    // Handle the deleting of all comments
                    $this->comment->delete(
                        array(
                            'ride_id' => $ride_id
                        )
                    );

                    // Finally, delete the ride
                    $this->ride->delete(
                        array(
                            'id' => $ride_id
                        )
                    );

                    echo json_encode(
                        array(
                            'status' => 'success',
                            'message' => "Ride successfully deleted."
                        )
                    );
                } else {
                    echo json_encode(
                        array(
                            'status' => 'fail',
                            'message' => 'Rides with passengers cannot be deleted. Contact Wheelzo for assistance.'
                        )
                    );
                }
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