<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
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
        }

        // Use user ID as the index key
        $temp_users = array();
        foreach( $users as $user ) {
            $temp_users[$user->id] = $user;
        }        

        $this->blade->render('main', 
            array(
                'rides' => $temp_rides,
                'users' => $temp_users
            )
        );
    }
}