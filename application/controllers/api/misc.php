<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Misc extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        
        $this->load->library('Facebook', 
            array(
                "appId" => "282192178572651", 
                "secret" => "4e20bf730703c8086620ba26693de1c2"
            )
        );

        $this->user = $this->facebook->getUser();
        
    }

    // Referenced from:
    //  http://phpguidance.wordpress.com/2013/09/27/facebook-login-with-codeignator/comment-page-1/
    public function logout_get() {
        session_destroy();
        redirect( base_url() );
    }
}