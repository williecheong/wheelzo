<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');
require_once(APPPATH.'libraries/stripe-php-2.3.0/init.php');

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

    public function index_post() {
        if ( !$this->wheelzo_user_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User is not logged in");
            return;
        }

        $data = clean_input( $this->post() );

        $passenger_id = $this->wheelzo_user_id;
        $ride_id = isset($data['rideID']) ? $data['rideID'] : '';
        $stripe_token = isset($data['stripeToken']) ? $data['stripeToken'] : '';
        $receipt_email = isset($data['receiptEmail']) ? $data['receiptEmail'] : '';

        if ( !$this->_verify_capacity($ride_id) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("The capacity of this ride is reached");
            return;
        }

        if ($stripe_token == '') {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Stripe token must not be empty");
            return;
        }

        if ($receipt_email == '') {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Email must be specified for receipt");
            return;
        }

        $user_ride_id = $this->user_ride->create(  
            array(  
                'user_id' => $passenger_id,
                'ride_id' => $ride_id
            )
        );

        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );
 
        $ride = $this->ride->retrieve_by_id( $ride_id );        
        $driver = $this->user->retrieve_by_id( $ride->driver_id );
        $passenger = $this->user->retrieve_by_id( $user_ride->user_id );

        try {
            \Stripe\Stripe::setApiKey(WHEELZO_STRIPE_SECRET_KEY);
            
            $amount_total = round(floatval($ride->price), 2);
            $amount_wheelzo = round(floatval(WHEELZO_PAYMENT_COMMISSION)*$amount_total, 2);
            $amount_driver = $amount_total - $amount_wheelzo;

            $chargeObject = array(
                'source' => $stripe_token,
                'amount' => $amount_total * 100,
                'currency' => 'cad',
                'receipt_email' => $receipt_email,
                'description' => "USER_RIDE ID: ".$user_ride->id,
                'metadata' => array(
                    'assignmentId' => $user_ride->id,

                    'rideId' => $ride->id,
                    'origin' => $ride->origin, 
                    'destination' => $ride->destination,
                    'start' => $ride->start,
                    
                    'driver_id' => $driver->id,
                    'driver_facebook_id' => $driver->facebook_id,
                    'driver_name' => $driver->name,
                    
                    'passenger_id' => $passenger->id,
                    'passenger_facebook_id' => $passenger->facebook_id,
                    'passenger_name' => $passenger->name,
                    
                    'amount_total' => $amount_total,
                    'amount_wheelzo' => $amount_wheelzo,
                    'amount_driver' => $amount_driver,
                    'commission' => WHEELZO_PAYMENT_COMMISSION
                )
            );

            $charge = Stripe\Charge::create($chargeObject);

        } catch (Exception $e) {
            $this->user_ride->delete(
                array('id' => $user_ride->id)
            );

            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message($e->getMessage());
            return;
        }

        // Log the transaction ID with the assignment
        $this->user_ride->update(
            array('id' => $user_ride->id),
            array('transaction' => $charge->id)
        );

        // Update the driver's balance
        $this->user->update(
            array('id' => $driver->id),
            array('balance' => strval(floatval($driver->balance) + $amount_driver) )
        );
                    
        $fb_response = false;
        
        if ( ENVIRONMENT == 'production' || in_array($driver->facebook_id, $GLOBALS['WHEELZO_TECH']) ) {
            try {
                $fb_response = $this->facebook->api(
                    '/' . $driver->facebook_id . '/notifications', 
                    'POST', 
                    array(
                        'href' => '/fb?goto='.$ride->id, 
                        'template' => '@[' . $passenger->facebook_id . '] has paid you for a ride scheduled on '. date( 'l, M j', strtotime($ride->start) ) .'.',
                        'access_token' => FB_APPID . '|' . FB_SECRET
                    )
                );
            } catch ( Exception $e ) {
                log_message('error', $e->getMessage() );
            }
        }

        if ( !$fb_response ) {
            http_response_code("200");
            header('Content-Type: application/json');
            echo $this->message($driver->name." successfully paid but could not be notified on Facebook.");
            return;
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message($driver->name." successfully paid and notified on Facebook.");
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
