<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Users extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
    }

    public function index_get() {
        $users = $this->user->retrieve();
        
        foreach( $users as $user ) {
            // Only display public user information
            $temp_users[$user->id]['name'] = $user->name;
            $temp_users[$user->id]['facebook_id'] = $user->facebook_id;
            $temp_users[$user->id]['score'] = number_format(round(floatval($user->rating), 2), 2);
        }

        echo json_encode($temp_users);

        return;
    }
}