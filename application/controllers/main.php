<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Referenced from:
        //  http://phpguidance.wordpress.com/2013/09/27/facebook-login-with-codeignator/comment-page-1/
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );

        $this->facebook_user = $this->facebook->getUser();
        
        // Autoloaded Config, Helpers, Models
    }
    
	public function index() {
        // Send the resulting data array into the view

        $rides = $this->ride->retrieve();
        $users = $this->user->retrieve();

        // Use ride ID as the index key
        $temp_rides = array();
        foreach( $rides as $ride ) { 
            $temp_rides[$ride->id] = $ride; 
            $temp_rides[$ride->id]->passengers = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );
            $temp_rides[$ride->id]->comments = $this->comment->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );
        }

        // Use user ID as the index key
        $temp_users = array();
        foreach( $users as $user ) { 
            $temp_users[$user->id]['name'] = $user->name;
            $temp_users[$user->id]['facebook_id'] = $user->facebook_id;
        }

        $url = '';
        if ( $this->facebook_user ) {
            // Registers the facebook user if not already done.
            // Always returns the local user ID of this person from our database.
            $user_id = $this->user->try_register( $this->facebook_user );
            $this->session->set_userdata('user_id', $user_id);
            
            $url = $this->facebook->getLogouturl(
                array(
                    "next" => base_url() . 'api/misc/logout'
                )
            );
        } else {
            $url = $this->facebook->getLoginUrl(
                array(
                    "scope" => "email",
                    "display" => "page"
                )
            );
        }

        $this->blade->render('main', 
            array(
                'rides' => $temp_rides,
                'users' => $temp_users,
                'session' => $this->session->userdata('user_id'),
                'session_url' => $url 
            )
        );
    }
}