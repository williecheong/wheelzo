<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Users extends API_Controller {
    
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

    public function session_get() {
        $user_id = 0;
        if ($this->wheelzo_user_id) {
            $user_id = (int) $this->wheelzo_user_id;
        }

        $output = array(
            "user_id" => $user_id,
            "facebook_url" => $this->facebook_url
        );

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($output);
        return;
    }

    public function index_get( $current = false ) {
        $users = array();
        if ( $current ) { // request is coming in from a route
            if ( $this->wheelzo_user_id ) {
                $users = array(
                    $this->user->retrieve_by_id( 
                        $this->wheelzo_user_id 
                    )
                );
            }
        } else if ( $this->get('id') ) { // an id was specified
            $users = array(
                $this->user->retrieve_by_id( 
                    $this->get('id') 
                )
            );
        } else if ( $this->get('facebook_id') ) { // a facebook id was specified
            $users = array(
                $this->user->retrieve_by_fb( 
                    $this->get('facebook_id') 
                )
            );
        } else { // nothing special about this request, get all
            $users = $this->user->retrieve();            
        }

        $temp_users = array();
        if ( isset($users[0]->id) ) {
            foreach( $users as $key => $user ) {
                // Only display public user information
                $temp_user['id'] = $user->id;
                $temp_user['name'] = $user->name;
                $temp_user['facebook_id'] = $user->facebook_id;
                $temp_user['score'] = number_format(round(floatval($user->rating), 2), 2);
                $temp_users[] = $temp_user;
            }
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($temp_users);
        return;
    }
}