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
            try {
                $url = "https://graph.facebook.com/372772186164295/feed?limit=100&access_token=" . $token;
                $response = json_decode( rest_curl($url) );
                if ( !isset($response->error->message) ) {
                   if ( isset($response->data) ) {
                        $postings = array();                        
                        
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

                        http_response_code("200");
                        header('Content-Type: application/json');
                        echo json_encode($postings);   
                    } else {
                        http_response_code("400");
                        header('Content-Type: application/json');
                        echo $this->_message("Facebook did not return data");     
                    }
                } else {
                    http_response_code("400");
                    header('Content-Type: application/json');
                    echo $this->_message($response->error->message);         
                }
            } catch (Exception $e) {
                http_response_code("400");
                header('Content-Type: application/json');
                echo $this->_message("Could not reach Facebook API");     
            }
        } else {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->_message("Invalid access token specified");  
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