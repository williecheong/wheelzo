<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Tools extends REST_Controller {
    
    public function scrape_post() {
        $data = $this->post();

        $fb_group_html = isset($data['fb_group_html']) 
                            ? $data['fb_group_html'] 
                            : '' ;
        
        if ( trim($fb_group_html) == '' ) {
            $fb_group_html = $this->load->file("sample.txt", true);
        }

        $user_messages = array(); 
        $this->load->library('html_dom');
        $this->html_dom->loadHTML( $fb_group_html );
        
        $postings = $this->html_dom->find('div.mbm');

        foreach( $postings as $posting ) {
            $author_fb_id = $this->grabID( 
                $posting->find('div a[data-hovercard]', 0)->getAttr('data-hovercard') 
            );

            $message = $posting->find('div.userContentWrapper div.userContent', 0);
            try {
                $message_content = strip_tags( $message->innertext );
                $message_content = htmlspecialchars_decode( $message_content );

                $user_messages[] = array(
                    "facebook_id" => $author_fb_id,
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
}