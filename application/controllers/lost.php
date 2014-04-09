<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lost extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models 
    }

    public function index() {
        $this->load->view('templates/404_not_found');
    }

}