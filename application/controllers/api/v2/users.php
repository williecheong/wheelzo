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

    public function permissions_get() {
        if ($this->wheelzo_user_id == false) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User not logged in");
            return;
        }

        try {
            $permissions = $this->facebook->api('/me/permissions');
        } catch (Exception $e) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Could not retrieve facebook permissions");
            return;
        }

        $output = array();
        if (isset($permissions['data'])) {
            foreach ($permissions['data'] as $permission) {
                if (!isset($permission['permission']) || !isset($permission['status'])) {
                    continue;
                }
                $output[$permission['permission']] = ($permission['status'] == 'granted'); 
            }
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($output);
        return;
    }

    public function statistics_get() {
        if ($this->wheelzo_user_id == false) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User not logged in");
            return;
        }

        $user = $this->user->retrieve_by_id($this->wheelzo_user_id);
        $driven_rides = $this->ride->retrieve(
            array(
                'driver_id' => $this->wheelzo_user_id
            )
        );

        $output = array(
            'balance' => $user->balance,
            'total_comments' => count(
                $this->comment->retrieve(
                    array(
                        'user_id' => $this->wheelzo_user_id
                    )
                )
            ),
            'total_reviews_written' => count(
                $this->review->retrieve(
                    array(
                        'giver_id' => $this->wheelzo_user_id
                    )
                )
            ),
            'total_carpools_taken' => count(
                $this->user_ride->retrieve(
                    array(
                        'user_id' => $this->wheelzo_user_id
                    )
                )
            ),
            'total_rides' => count($driven_rides),
            'total_passengers' => 0 
        );

        foreach ( $driven_rides as $ride ) {
            $output['total_passengers'] += count(
                $this->user_ride->retrieve(
                    array(
                        'ride_id' => $ride->id
                    )
                )
            );
        }

        $output['reviews'] = $this->review->retrieve(
            array(
                'receiver_id' => $this->wheelzo_user_id
            )
        );

        $output['points'] = $this->point->retrieve(
            array(
                'receiver_id' => $this->wheelzo_user_id
            )
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
            $users = array();
            $user = $this->user->retrieve_by_id($this->get('id'));
            if ($user) {
                $users[] = $user;
            }
        } else if ( $this->get('facebook_id') ) { // a facebook id was specified
            $users = array();
            $user = $this->user->retrieve_by_fb($this->get('facebook_id'));
            if ($user) {
                $users[] = $user;
            }
        } else if ( $this->get('name') ) { // a name was specified
            $users = $this->user->retrieve_like(
                array(
                    'name' => $this->get('name')
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