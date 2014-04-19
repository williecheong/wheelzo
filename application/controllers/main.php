<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Facebook login referenced from:
        //  http://phpguidance.wordpress.com/2013/09/27/facebook-login-with-codeignator/comment-page-1/
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
        
        $this->facebook_url = '';    
        $this->facebook_user = $this->facebook->getUser();
        
        if ( $this->facebook_user ) {
            // Registers the facebook user if not already done.
            // Always returns the local user ID of this person from our database.
            $user = $this->user->try_register( $this->facebook_user );
            
            $this->session->set_userdata('user_id', $user->id);
            $this->session->set_userdata('email', $user->email);
            
            $this->facebook_url = $this->facebook->getLogouturl(
                array(
                    "next" => base_url() . 'api/misc/logout'
                )
            );
        
        } else {
            $this->facebook_url = $this->facebook->getLoginUrl(
                array(
                    "scope" => "email",
                    "display" => "page"
                )
            );
        }
    }
    
	public function index() {
        // Use user ID as the index key
        $temp_users = array();
        $users = $this->user->retrieve();
        foreach( $users as $user ) {
            // Only display public user information
            $temp_users[$user->id]['name'] = $user->name;
            $temp_users[$user->id]['facebook_id'] = $user->facebook_id;
        }

        $this->blade->render('main', 
            array(
                'users' => $temp_users,
                'rides' => $this->ride->retrieve_relevant(),
                'session' => $this->session->userdata('user_id'),
                'session_url' => $this->facebook_url
            )
        );
    }
}