<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V2 extends CI_Controller {

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
        
        try {
            // This will verify that the token is not broken
            $this->facebook->getUser(); 
            $this->facebook->api('/me');
            
            // Registers the facebook user if not already done.
            // Always returns the local user ID of this person from our database.
            $user = $this->user->try_register( 
                $this->facebook->getUser() 
            );

            $this->wheelzo_facebook_id = $user->facebook_id;
            $this->wheelzo_user_id = $user->id;

            $this->facebook_url = $this->facebook->getLogouturl(
                array(
                    "next" => base_url() . 'logout'
                )
            );
        
        } catch ( Exception $e ) {
            $this->wheelzo_facebook_id = false;
            $this->wheelzo_user_id = false;

            $this->facebook_url = $this->facebook->getLoginUrl(
                array(
                    "scope" => "email,manage_notifications",
                    "display" => "page"
                )
            );
        }
    }

    public function index() {
        $this->blade->render('v2/main');
    }
}