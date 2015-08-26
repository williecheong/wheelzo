<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class Fbgroups extends API_Controller {
    
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

    public function index_get() { // retrieve all facebook groups
        $fbgroups = $this->fbgroup->retrieve();

        $output = array();
        foreach ($fbgroups as $fbgroup) {
            $output[] = array(
                'name' => $fbgroup->name,
                'facebook_id' => $fbgroup->facebook_id
            );
        }

        http_response_code("200");
        header('Content-Type: application/json');
        echo json_encode($output);
        return;
    }

    public function index_post() { // add new group, watch out for duplicates
        if ($this->wheelzo_user_id == false) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("User not logged in");
            return;
        }

        $data = clean_input( $this->post() );

        if ( !isset($data['facebookId']) ){
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Required information is missing");
            return;
        }

        $facebook_id = $data['facebookId'];
        if ( !ctype_digit($facebook_id) || strlen($facebook_id) < 10 ){
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Facebook group id is invalid");
            return;
        }

        $fbgroup = $this->fbgroup->retrieve_by_fb($facebook_id);
        if ($fbgroup != false) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Facebook group already exists as ".$fbgroup->name);
            return;
        }

        try {
            $retrieved_fbgroup = $this->facebook->api($facebook_id);    
        } catch(Exception $e) {
            // do nothing
        }

        if (!isset($retrieved_fbgroup['name']) || !isset($retrieved_fbgroup['id'])) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Facebook group not found");
            return;            
        }

        if ($retrieved_fbgroup['id'] != $facebook_id) {
            http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Error in retrieving Facebook group");
            return;            
        }

        $group_name = strtolower($retrieved_fbgroup['name']);
        $keywords = array(
            "rideshare", "ride share", "ride-share", 
            "carpool", "car pool", "car-pool",
            "covoiturage"
        );
        $keywordFound = false;
        foreach ($keywords as $keyword) {
            if (string_contains($keyword, $group_name)) {
                $keywordFound = true;
                break;
            }
        }

        if ($keywordFound == false) {
           http_response_code("400");
            header('Content-Type: application/json');
            echo $this->message("Wheelzo has determined that this is not a relevant rideshare group. Please contact us if you believe that we have made a mistake here.");
            return;            
        }

        $this->fbgroup->create(
            array(
                'name' => $retrieved_fbgroup['name'],
                'facebook_id' => $retrieved_fbgroup['id'],
                'introduced_by' => $this->wheelzo_user_id
            )
        );

        http_response_code("200");
        header('Content-Type: application/json');
        echo $this->message("Facebook group successfully created");
        return;
    }
}
