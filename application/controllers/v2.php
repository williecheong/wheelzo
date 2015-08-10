<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V2 extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->blade->render('v2/main',
            array(
                'requested_ride' => $this->ride->retrieve_by_id($this->input->get('ride')),
                'requested_user' => $this->user->retrieve_by_id($this->input->get('user')),
                'day_filters' => array(
                    date('l', strtotime("now")),
                    date('l', strtotime("+1 day")),
                    date('l', strtotime("+2 days")),
                    date('l', strtotime("+3 days")),
                )
            )
        );
    }

    public function lookup() {
        $this->blade->render('v2/lookup');
    }
}