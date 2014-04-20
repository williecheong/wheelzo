<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fb extends CI_Controller {

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
        }
    }
    
    public function index() {
        $go_to_ride = '';
        if ( $this->input->get('goto') ) {
            $go_to_ride = $this->input->get('goto');
        }

        $this->blade->render('fb_landing',
            array(
                'ride_link' => '/me?search=' . encode_to_chinese($go_to_ride)
            )
        );
    }
}