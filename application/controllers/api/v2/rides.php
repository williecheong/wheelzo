<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Rides extends API_Controller {
    
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

    public function index_get( $load_personal = false ) {
        $rides = array();
        if ( $load_personal ) {
            $rides = $this->ride->retrieve_personal();
        } else {
            $rides = $this->ride->retrieve_active();
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($rides);
        return;
    }

    public function isdriver_get() {
        if ($this->wheelzo_user_id == false) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User not logged in");
            return;
        }

        $rides = $this->ride->retrieve_where_user_is_driver();
        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($rides);
        return;
    }

    public function ispassenger_get() {
        if ($this->wheelzo_user_id == false) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User not logged in");
            return;
        }

        $rides = $this->ride->retrieve_where_user_is_passenger();
        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($rides);
        return;
    }

    public function search_get() {
        $rides = array();
        if ( $this->get('id') ) {
            $rides = array();
            $ride = $this->ride->retrieve_by_id($this->get('id'));
            if ($ride) {
                $rides[] = $ride;
            }
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($rides);
        return;
    }

    public function index_post() {
        if ( !$this->wheelzo_user_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
            return;
        }          
            
        $data = clean_input( $this->post() );

        $driver_id = $this->wheelzo_user_id;
        $origin = isset($data['origin']) ? $data['origin'] : '';
        $destination = isset($data['destination']) ? $data['destination'] : '';

        if ( $origin == '' || $destination == '' ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Origin and destination cannot be empty");
            return;
        }

        // if unspecified, break with invalid string
        $departure_date = isset($data['departureDate']) ? $data['departureDate'] : 'null';
        $departure_time = isset($data['departureTime']) ? $data['departureTime'] : 'null';
        $start = strtotime( $departure_date . ' ' . $departure_time );

        if ( $start ) {
            $start = date('Y-m-d H:i:s', $start);
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Unable to recognize date time format");
            return;
        }

        $capacity = isset($data['capacity']) ? $data['capacity'] : '1';
        $price = isset($data['price']) ? $data['price'] : '0';

        if ( !ctype_digit($capacity) || !ctype_digit($price) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Price and capacity must be numeric");
            return;
        }

        if ($capacity < 1 || $capacity > 7) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Capacity must be between 1 and 7");
            return;
        }

        if ($price < 1 || $price > 60) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Price must be between 1 and 60");
            return;
        }

        $drop_offs = isset($data['dropOffs']) ? implode(WHEELZO_DELIMITER, $data['dropOffs']) : '';

        $allow_payments = isset($data['allowPayments']) ? $data['allowPayments'] : 0;
        if ($allow_payments != 0 && $allow_payments != 1) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Invalid payment option provided");
            return;
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
            if ( !$rrequest ) {
                $output_message .= " Ride request not found.";
                continue;
            }

            $passenger = $this->user->retrieve_by_id( $rrequest->user_id );
            
            if ( !$passenger ) {
                $output_message .= " Passenger of ride request not found.";
                continue;
            }
                    
            $notification_type = $ride->id . NOTIFY_INVITED; 
            $to_notify = $this->user->to_notify( $driver->id, $notification_type );

            if ( !$to_notify ) {
                $output_message .= " " . $passenger->name . " was already notified on Facebook about this invitation.";
                continue;
            }

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

            if ( !$fb_response ) {
                $output_message .= " " . $passenger->name . " could not be notified on Facebook about this invitation.";
                continue;
            }

            $output_message .= " " . $passenger->name . " successfully notified on Facebook about this invitation.";
            $success = $this->rrequest->add_invitation( $invitee, $ride->id );
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message($output_message);        
        return;
    }

    public function index_delete( $ride_id = '' ) {
        if ( !$this->wheelzo_user_id ) {            
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
            return;
        }

        $driver_id = $this->wheelzo_user_id;
        
        if ( !$this->_verify_driver( $ride_id, $driver_id) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("You are not the driver. Stop hacking.");
            return;
        }

        $user_rides = $this->user_ride->retrieve(
            array(
                'ride_id' => $ride_id
            )
        );

        if (count($user_rides) > 0) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Rides with passengers cannot be deleted. Please contact Wheelzo for further assistance.");
            return;
        }

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

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Ride successfully deleted.");
        return;    
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