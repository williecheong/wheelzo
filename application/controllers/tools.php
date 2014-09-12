<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

    public function index() {
        echo date("Y-m-d H:i:s");
        phpinfo();
    }

    public function fbscrape() {
        $this->blade->render('/tools/fbscrape');

    }
}