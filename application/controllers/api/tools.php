<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Tools extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        if ( !in_array($this->session->userdata('facebook_id'), unserialize(WHEELZO_ADMINS)) ) {
            redirect( base_url() );
        }
    }

    public function fetch_messages_get() {
        $token = $this->get('token');
        if ( $token ) {
            $facebook_groups = array(
                '372772186164295',  // University of Waterloo Carpool
                '231943393631223'   // Rideshare Wilfred Laurier               
            );

            $postings = array();                        

            foreach ( $facebook_groups as $facebook_group ) {
                try {
                    $url = "https://graph.facebook.com/" . $facebook_group . "/feed?limit=100&access_token=" . $token;
                    $response = json_decode( rest_curl($url) );
                    if ( !isset($response->error->message) ) {
                       if ( isset($response->data) ) {
                            foreach ($response->data as $key => $posting) {
                                if ( isset($posting->from->id) ) {
                                    // Check to see if this is a wheelzo user
                                    if ( $this->user->retrieve_by_fb($posting->from->id) ) {
                                        // Check to see if this posting has been made before
                                        if ( isset($posting->id) ) {
                                            $this->load->model('facebook_ride');
                                            if ( !$this->facebook_ride->retrieve_by_fb($posting->id) ) {
                                                $postings[] = $posting;
                                            }                                        
                                        }
                                    }
                                }
                            }  
                        } else {
                            // No error was found, but data is missing from response     
                        }
                    } else {
                        // Token was not valid for accessing this group
                    }
                } catch (Exception $e) {
                    http_response_code("400");
                    header('Content-Type: application/json');
                    echo $this->_message("Could not reach Facebook API");
                    return;
                }
            }
            
            if ( count($postings) == 0 ) {
                http_response_code("404");
                header('Content-Type: application/json');
                echo $this->_message("No postings found. Check facebook token.");
                return;
            } else {
                http_response_code("200");
                header('Content-Type: application/json');
                echo json_encode($postings);
                return;
            }
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->_message("Invalid access token specified");  
            return;
        }
    }

    private function _message( $message = "" ) {
        return json_encode(
            array(
                "message" => $message
            )
        );
    }
}