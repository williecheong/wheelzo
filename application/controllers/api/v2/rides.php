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

    public function index_get() {
        // set to false for no ID mapped objects
        $rides = $this->ride->retrieve_active(false);
        
        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($rides);
        return;
    }

    public function me_get() {
        // set to false for no ID mapped objects
        $rides = $this->ride->retrieve_personal(false);

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
        $departure_date = isset($data['departureDate']) ? $data['departureDate'] : '';
        $departure_time = isset($data['departureTime']) ? $data['departureTime'] : '';
        $capacity = isset($data['capacity']) ? $data['capacity'] : '1';
        $price = isset($data['price']) ? $data['price'] : '0';

        $start = strtotime( $departure_date . ' ' . $departure_time );
        $start = date('Y-m-d H:i:s', $start);

        $drop_offs = isset($data['dropOffs']) ? implode(WHEELZO_DELIMITER, $data['dropOffs']) : '';

        $ride_id = $this->ride->create(  
            array(  
                'driver_id' => $driver_id,
                'origin' => $origin,
                'destination' => $destination,
                'capacity' => $capacity,
                'price' => $price,
                'start' => $start,
                'drop_offs' => $drop_offs   
            )
        );
        $ride = $this->ride->retrieve_by_id( $ride_id );
        $output_message = "Ride successfully posted.";

        $driver = $this->user->retrieve_by_id( $driver_id );
        $invitees = isset($data['invitees']) ? $data['invitees'] : array();
        foreach( $invitees as $invitee ) {
            $rrequest = $this->rrequest->retrieve_by_id( $invitee );
            if ( !$rrequest ) {
                $output_message .= " Ride request not found. Stop hacking.";
                continue;
            }

            $passenger = $this->user->retrieve_by_id( $rrequest->user_id );
            
            if ( !$passenger ) {
                $output_message .= " Passenger of ride request not found. So weird.";
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
            
            if ( $old_user_ride->user_id == $driver_id ) {
                $output_message .= "You were successfully removed from your own ride. ";
                continue;
            }
                
            $driver = $this->user->retrieve_by_id( $ride->driver_id );
            $old_passenger = $this->user->retrieve_by_id( $old_user_ride->user_id );
            
            $notification_type = $ride->id . NOTIFY_DELETED;
            $to_notify = $this->user->to_notify( $old_passenger->id, $notification_type );
            
            if ( !$to_notify ) {        
                $output_message .= $old_passenger->name." successfully removed and already notified on Facebook. ";
                continue;
            }

            $fb_response_to_old = false;
            
            if ( ENVIRONMENT == 'production' || in_array($old_passenger->facebook_id, $GLOBALS['WHEELZO_TECH']) ) {
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
            }

            if ( !$fb_response_to_old ) {
                $output_message .= $old_passenger->name." successfully removed but could not be notified on Facebook. ";
                continue;
            }  

            $output_message .= $old_passenger->name." successfully removed and notified on Facebook. ";    
        }

        // Finally, delete the ride
        $this->ride->delete(
            array(
                'id' => $ride_id
            )
        );

        $output_message .= "Ride successfully deleted.";

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message($output_message);
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