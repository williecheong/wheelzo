<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Misc extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models 
    }

    // Referenced from:
    //  https://github.com/EllisLab/CodeIgniter/wiki/Persona-Login
    public function login_post() {
        if ( isset($_POST['assertion']) ) {
            $this->authentication->login($_POST['assertion']);
        }

        if ( $this->session->userdata('email') ) {
            $admins = $this->administrator->retrieve(array('email' => $this->session->userdata('email')));
            if ( count($admins) < 1 ) {
                $this->authentication->logout();
                echo "Not administrator";
            } else {
                echo "OK";
            }
        } else {
            echo "No session was created.";            
        }

        return;
    }

    // Referenced from:
    //  https://github.com/EllisLab/CodeIgniter/wiki/Persona-Login
    public function logout_post() {
        $this->authentication->logout();
    }
}