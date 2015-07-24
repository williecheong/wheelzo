<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

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
        } catch ( Exception $e ) {
            $this->wheelzo_facebook_id = false;
            $this->wheelzo_user_id = false;
        }
    }

    public function index() {
        phpinfo();
    }

    public function facebook_import() {
        if ( in_array($this->wheelzo_facebook_id, $GLOBALS['WHEELZO_BDEV']) ) {
            $this->load->view('/tools/facebook_import', 
                array(
                    'accessToken' => WHEELZO_FACEBOOK_ACCESS_TOKEN
                )
            );
        } else {
            redirect( base_url() );
        }
    }

}