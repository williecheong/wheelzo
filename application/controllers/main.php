<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models 
    }
    
	public function index() {
        // Send the resulting data array into the view
        $rides = $this->ride->retrieve();

        $this->blade->render('main', 
            array(
                'rides' => $rides
            )
        );
    }
}