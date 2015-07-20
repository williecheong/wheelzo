<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Facebook_import extends API_Controller {
    
    function __construct() {
        parent::__construct();
        if ( in_array($this->wheelzo_facebook_id, $GLOBALS['WHEELZO_BDEV']) ) {
            $this->load->model('facebook_ride');
        } else {
            redirect( base_url() );            
        }
    }
    
    public function ping_get() {
        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("OK");
        return;
    }

    public function fetch_messages_get() {
        $token = $this->get('token');
        if ( !$token ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Invalid access token specified");  
            return;
        }

        $facebook_groups = array(
            '372772186164295',  // University of Waterloo Carpool
            '231943393631223'   // Rideshare Wilfred Laurier               
        );

        $postings = array();                     
        $response_data = array();
        foreach ( $facebook_groups as $facebook_group ) {
            try {
                $url = "https://graph.facebook.com/" . $facebook_group . "/feed?limit=100&access_token=" . $token;
                $response = json_decode( rest_curl($url) );
                
                if ( isset($response->error->message) ) { // Token was not valid for accessing this group
                    continue;
                }

                if ( !isset($response->data) ) { // No error was found, but data is missing from response     
                    continue;
                }

                $response_data = $response_data + $response->data;
                foreach ($response->data as $key => $posting) {
                    
                    if ( !isset($posting->from->id) ) {
                        continue;
                    }
                    
                    $driver = $this->user->retrieve_by_fb( $posting->from->id );
                    
                    if ( !$driver ) { // Check to see if this is a wheelzo user
                        if ($this->user->retrieve_by_name($posting->from->name) != false) {
                            // this user might already exist
                            // stop to avoid potential duplicate
                            continue;
                        }

                        $user_id = $this->user->create(  
                            array(
                                'facebook_id' => $posting->from->id,
                                'name' => $posting->from->name
                            )
                        );
                        
                        $driver = $this->user->retrieve_by_id($user_id);
                    }
                    
                    if ( !isset($posting->id) ) {
                        continue;
                    }
                    
                    if ( $this->facebook_ride->retrieve_by_fb($posting->id) ) { // Check to see if this posting has been made before
                        continue;
                    }
                    
                    $url = "http://ec2-54-148-33-40.us-west-2.compute.amazonaws.com:3000/nlpApi";
                    $type = "POST";
                    $params = (object) array(
                        "message"   => $posting->message,
                        "timestamp" => $posting->updated_time
                    );

                    $processed_ride = json_decode( rest_curl($url, $type, $params) );
                    
                    $posting->activeRides = $this->ride->retrieve_active_by_user($driver->id);
                    
                    if ( !isset($processed_ride) ) { // NLP did not return a valid ride. Not too sure what happened there. Send posting to front for judging
                        $postings[] = $posting; 
                        continue;
                    }
                    
                    if ( !$this->_validate_processedRide_exists($processed_ride) ) { // This must be a passenger posting
                        continue;
                    }
                    
                    if ( $processed_ride->origin || $processed_ride->destination || $processed_ride->departure || $processed_ride->capacity || $processed_ride->price ) {
                        $posting->processedRide = $processed_ride;
                        $postings[] = $posting;
                        continue;
                    }
                }
            } catch (Exception $e) {
                http_response_code("400");
                header('Content-Type: application/json');
                echo $this->message("Could not reach Facebook API");
                return;
            }
        }
        
        if ( count($postings) != 0 ) { // We have work to do
            http_response_code("200");
            header('Content-Type: application/json');
            echo json_encode($postings);
            return;
        }

        if ( count($response_data) == 0 ) {
            http_response_code("404");
            header('Content-Type: application/json');
            echo $this->message("Check facebook token");
            return;
        }


        // There were postings returned from facebook, but nothing to process
        // Because good administrator work deserves a little extra processing power
        $admin = $this->user->retrieve_by_id( $this->wheelzo_user_id );
        $good_work_message = "Nothing to import for now. Good job!";
        if ( isset($admin->name) ) {
            $good_work_message = "Nothing to import for now. Good job, " . $admin->name . "!";
        }
        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message($good_work_message);
        return;    
    }

    public function forget_ride_post() {
        $posting = array_to_object( $this->post('posting') );
        if ( !$this->_validate_posting($posting) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Invalid posting specified");  
            return;
        }

        $posting->to->data = (array)$posting->to->data;
        $mapping_id = $this->facebook_ride->create(
            array(
                'ride_id' => 0,
                'facebook_post_id' => $posting->id
            ) 
        );

        if ( !$mapping_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Ride posting could not be forgotten");  
            return;
        }    

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Ride posting has been forgotten");  
        return;    
    }

    public function import_ride_post() {
        $posting = array_to_object( $this->post('posting') );
        if ( !$this->_validate_posting($posting) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Invalid posting specified");  
            return;
        }   

        $posting->to->data = (array)$posting->to->data;
        if ( !$this->_validate_processedRide($posting->processedRide) ) {   
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Invalid parameters specified in posting");  
            return;
        }    

        if ( $this->facebook_ride->retrieve_by_fb($posting->id) ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Ride posting has been imported/forgotten before");  
            return;
        }

        $driver = $this->user->retrieve_by_fb( $posting->from->id );
        if ( !$driver ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Driver is not a registered user");
            return;
        }

        $ride_id = $this->ride->create(  
            array(  
                'driver_id'     => $driver->id,
                'origin'        => $posting->processedRide->origin,
                'destination'   => $posting->processedRide->destination,
                'capacity'      => $posting->processedRide->capacity,
                'price'         => $posting->processedRide->price,
                'start'         => date('Y-m-d H:i:s', strtotime($posting->processedRide->departure)),
                'drop_offs'     => ''   
            )
        );

        if ( !$ride_id ) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Ride could not be created");
            return;
        }

        $mapping_id = $this->facebook_ride->create(
            array(
                'ride_id' => $ride_id,
                'facebook_post_id' => $posting->id
            )
        );

        $comment_id = $this->comment->create(  
            array(  
                'user_id' => $this->wheelzo_user_id,
                'ride_id' => $ride_id,
                'comment' => '<em>Ride imported from <a href="//facebook.com/' . $posting->id . '" target="_blank">' . $posting->to->data[0]->name . '</a></em>',
                'last_updated' => date( 'Y-m-d H:i:s' )
            )
        );

        $notification_type = $ride_id . NOTIFY_IMPORT; 
        $to_notify = $this->user->to_notify( $driver->id, $notification_type );
        if ( !$to_notify ) {
            http_response_code("200");
            header('Content-Type: application/json');
            echo $this->message("Ride posting has been imported, but driver has been notified before.");  
            return;
        }

        $fb_response = false;
        if ( ENVIRONMENT == 'production' || in_array($driver->facebook_id, $GLOBALS['WHEELZO_TECH']) ) {
            try {
                $fb_response = $this->facebook->api(
                    '/' . $driver->facebook_id . '/notifications', 
                    'POST', 
                    array(
                        'href' => '/fb?goto='.$ride_id,
                        'template' => 'Your ride on ' . $posting->to->data[0]->name . ' has been imported into Wheelzo.',
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
            echo $this->message("Ride posting has been imported, but driver could not be notified.");  
            return;
        }  

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Ride posting has been imported. Driver has been notified.");  
        return;
    }

    private function _validate_posting( $posting = array() ) {
        if ( isset($posting->id) ) {
            if ( isset($posting->from->id) ) {
                $posting->to->data = (array)$posting->to->data;
                if ( count($posting->to->data) > 0 ) {
                    if ( isset($posting->to->data[0]->id) && isset($posting->to->data[0]->name) ) {
                        if ( isset($posting->processedRide) ) {
                            return $this->_validate_processedRide_exists( $posting->processedRide );
                        }
                    }    
                }
            }
        }
        return false;
    }

    private function _validate_processedRide_exists( $ride = array() ) {
        if ( property_exists($ride, "origin") 
          && property_exists($ride, "destination") 
          && property_exists($ride, "departure") 
          && property_exists($ride, "capacity") 
          && property_exists($ride, "price") ) {
            return true;
        }
        return false;
    }

    private function _validate_processedRide( $ride = array() ) {
        if ( $ride->origin && $ride->destination ) {
            if ( is_numeric($ride->capacity) && is_numeric($ride->price) ) {
                if ( $ride->capacity <= 7 && $ride->price <= 35 ) {
                    $stamp = strtotime( $ride->departure );
                    $month = date( 'm', $stamp );
                    $day   = date( 'd', $stamp );
                    $year  = date( 'Y', $stamp );
                    if ( checkdate($month, $day, $year) ) {
                        return true;
                    } else {
                        return false; 
                    }
                }
            }
        }
        return false;
    }
}
