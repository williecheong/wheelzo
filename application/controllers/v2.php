<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V2 extends CI_Controller {

    function __construct() {
        parent::__construct();// Facebook login referenced from:
        //  http://phpguidance.wordpress.com/2013/09/27/facebook-login-with-codeignator/comment-page-1/
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
        
        $this->session_exists = false;
        $this->wheelzo_facebook_id = 0;
        try { // This will verify that the token is not broken
            $facebook_id = $this->facebook->getUser(); 
            if ($facebook_id != 0) {
                $this->session_exists = true;
                $this->wheelzo_facebook_id = $facebook_id; 
            }
        } catch ( Exception $e ) {
            // Do nothing
        }
    }

    public function index() {
        $this->blade->render('v2/main',
            array(
                'requested_ride' => $this->ride->retrieve_by_id($this->input->get('ride')),
                'requested_user' => $this->user->retrieve_by_id($this->input->get('user'))
            )
        );
    }

    public function lookup() {
        $this->blade->render('v2/lookup');
    }

    public function profile() {
        if ($this->session_exists == false) {
            redirect(base_url());
        }
        $this->blade->render('v2/profile');
    }
    
    public function privacy() {
        $this->blade->render('v2/privacy');
    }
}