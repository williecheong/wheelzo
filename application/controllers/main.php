<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => "282192178572651", 
                "secret" => "4e20bf730703c8086620ba26693de1c2"
            )
        );

        $this->facebook_user = $this->facebook->getUser();

    }
    
	public function index() {
        // Send the resulting data array into the view
        $rides = $this->ride->retrieve();
        $users = $this->user->retrieve();

        // Use ride ID as the index key
        $temp_rides = array();
        foreach( $rides as $ride ) {
            $temp_rides[$ride->id] = $ride;
        }

        // Use user ID as the index key
        $temp_users = array();
        foreach( $users as $user ) {
            $temp_users[$user->id] = $user;
        }        

        $url = '';
        if ( $this->facebook_user ) {
            $user_id = $this->user->try_register( $this->facebook_user );
            $url = $this->facebook->getLogouturl(
                array(
                    "next" => base_url()."api/misc/logout"
                )
            );
        } else {
            $url = $this->facebook->getLoginUrl(
                array(
                    "scope" => "email"
                )
            );
        }

        $this->blade->render('main', 
            array(
                'rides' => $temp_rides,
                'users' => $temp_users,
                'session' => $this->facebook_user,
                'session_url' => $url 
            )
        );
    }
}