<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Users extends API_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }

    public function index_get() {
        $users = $this->user->retrieve();
        
        foreach( $users as $user ) {
            // Only display public user information
            $temp_users[$user->id]['name'] = $user->name;
            $temp_users[$user->id]['facebook_id'] = $user->facebook_id;
            $temp_users[$user->id]['score'] = number_format(round(floatval($user->rating), 2), 2);
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($temp_users);
        return;
    }
}