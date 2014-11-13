<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
    
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

    // Referenced from:
    //  http://phpguidance.wordpress.com/2013/09/27/facebook-login-with-codeignator/comment-page-1/
    public function index() {
        $this->facebook->destroySession();
        $this->session->sess_destroy();
        redirect( base_url() );
    }
}