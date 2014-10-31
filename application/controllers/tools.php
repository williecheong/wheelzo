<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ( !in_array($this->session->userdata('facebook_id'), unserialize(WHEELZO_ADMINS)) ) {
            redirect( base_url() );
        }
    }

    public function index() {
        phpinfo();
    }

    public function facebook_import() {
        $this->load->view('/tools/facebook_import');
    }

}