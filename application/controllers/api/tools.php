<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Tools extends REST_Controller {
    
    public function scrape_post() {
        $fb_group_html = isset($_FILES['fb_group_html']['tmp_name']) 
                            ? file_get_contents($_FILES['fb_group_html']['tmp_name']) 
                            : '' ;
        
        if ( trim($fb_group_html) == '' ) {
            $fb_group_html = $this->load->file("assets/uploads/sample.txt", true);
        }

        $user_messages = array(); 
        $this->load->library('html_dom');
        $this->html_dom->loadHTML( $fb_group_html );
        
        $postings = $this->html_dom->find('div.userContentWrapper');

        foreach( $postings as $posting ) {
            $author_fb_id = $this->grabID( 
                $posting->find('div.clearfix a', 0)->getAttr('data-hovercard') 
            );

            $fb_post_url = $posting->find('div.clearfix div div div div div span span a', 0)->getAttr('href');
            $message = $posting->find('div.userContent', 0);
            try {
                $message_content = strip_tags( $message->innertext );
                $message_content = htmlspecialchars_decode( $message_content );

                $user_messages[] = array(
                    "facebook_id" => $author_fb_id,
                    "postings_id" => $fb_post_url,
                    "raw_message" => $message_content
                );
            } catch ( Exception $e ) {
                // skip and do nothing
            }
        }

        if ( empty($user_messages) ) {
            var_dump( $fb_group_html );
        } else {
            header('Content-Type: application/json');
            echo indent( json_encode($user_messages) );
        }
    }

    private function grabID( $url ) {
        $exploded_url = explode("id=", $url);
        $url = $exploded_url[1];

        $exploded_url = explode("&", $url);
        return $exploded_url[0];
    }

    private function getRealPOST() {
        $pairs = explode("&", file_get_contents("php://input"));
        $vars = array();
        foreach ($pairs as $pair) {
            $nv = explode("=", $pair);
            $name = urldecode($nv[0]);
            $value = urldecode($nv[1]);
            $vars[$name] = $value;
        }
        return $vars;
    }
}