<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

    public function index() {
        return date();
    }

    public function fbscrape() {
        $this->blade->render('/tools/fbscrape');

    }
}