<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Facebook login referenced from:
        //  http://phpguidance.wordpress.com/2013/09/27/facebook-login-with-codeignator/comment-page-1/
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
        
        $this->facebook_url = '';    
        $this->facebook_user = $this->facebook->getUser();
        
        if ( $this->facebook_user ) {
            // Registers the facebook user if not already done.
            // Always returns the local user ID of this person from our database.
            $user = $this->user->try_register( $this->facebook_user );
            
            $this->session->set_userdata('user_id', $user->id);
            $this->session->set_userdata('email', $user->email);
            
            $this->facebook_url = $this->facebook->getLogouturl(
                array(
                    "next" => base_url() . 'api/misc/logout'
                )
            );
        
        } else {
            $this->facebook_url = $this->facebook->getLoginUrl(
                array(
                    "scope" => "email,manage_notifications",
                    "display" => "page"
                )
            );
        }
    }
    
	public function index( $load_personal = false ) {
        // Is session available when user is requesting the personal page?
        if ( $load_personal ) {
            if ( !$this->session->userdata('user_id') ) {
                redirect( base_url() );
            }
        }

        // Use user ID as the index key
        $rides = array();
        if ( $load_personal ) {
            $rides = $this->ride->retrieve_personal();
        } else {
            $rides = $this->ride->retrieve_active();
        }

        $temp_users = array();
        $users = $this->user->retrieve();
        foreach( $users as $user ) {
            // Only display public user information
            $temp_users[$user->id]['name'] = $user->name;
            $temp_users[$user->id]['facebook_id'] = $user->facebook_id;
            $temp_users[$user->id]['score'] = $user->rating;
        }

        $view = 'main';
        $my_rrequests = array();
        if ( $load_personal ) {
            $view = 'me';
            
            $my_rrequests = $this->rrequest->retrieve(
                array(
                    'user_id' => $this->session->userdata('user_id')
                )
            );
            
            $temp_my_rrequests = array();
            
            foreach( $my_rrequests as $key => $my_rrequest ) {
                $invitations = explode( WHEELZO_DELIMITER, $my_rrequest->invitations);
                $temp_invitations = array();
                foreach( $invitations as $invitation ) {
                    $temp_ride = $this->ride->retrieve_by_id($invitation);
                    if ( $temp_ride ) {
                        $temp_invitations[] = $temp_ride; 
                    }
                }

                $my_rrequests[$key]->invitations = $temp_invitations;
                $temp_my_rrequests[$my_rrequest->id] = $my_rrequests[$key];
            }

            $my_rrequests = $temp_my_rrequests;
        }

        $this->blade->render($view, 
            array(
                'users' => $temp_users,
                'rides' => $rides,
                'session' => $this->session->userdata('user_id'),
                'session_url' => $this->facebook_url,
                'my_rrequests' => $my_rrequests,
                'rrequests' => $this->rrequest->retrieve_active(),
                'request_ride_id' => $this->input->get('ride')
            )
        );
    }
}