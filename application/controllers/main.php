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
        
        try {
            // This will verify that the token is not broken
            $this->facebook->getUser(); 
            $this->facebook->api('/me');
            
            // Registers the facebook user if not already done.
            // Always returns the local user ID of this person from our database.
            $user = $this->user->try_register( 
                $this->facebook->getUser() 
            );

            $this->wheelzo_facebook_id = $user->facebook_id;
            $this->wheelzo_user_id = $user->id;
            
            $this->facebook_url = $this->facebook->getLogouturl(
                array(
                    "next" => base_url() . 'logout'
                )
            );
        
        } catch ( Exception $e ) {
            $this->wheelzo_facebook_id = false;
            $this->wheelzo_user_id = false;

            $this->facebook_url = $this->facebook->getLoginUrl(
                array(
                    "scope" => "email,manage_notifications",
                    "display" => "page"
                )
            );
        }
    }
    
	public function index( $load_personal = false ) {
        // Is active user id available when user is requesting the personal page?
        if ( $load_personal ) {
            if ( !$this->wheelzo_user_id ) {
                redirect( base_url() );
            }
        }

        // Use user ID as the index key
        $rides = array();
        $mapped_rides = array();
        if ( $load_personal ) {
            $rides = $this->ride->retrieve_personal();
        } else {
            $rides = $this->ride->retrieve_active();
        }

        foreach( $rides as $key => $ride ) {
            $rides[$key]->comments = $this->comment->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );

            $rides[$key]->passengers = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );

            $mapped_rides[$ride->id] = $rides[$key];
        }

        $temp_users = array();
        $users = $this->user->retrieve();
        foreach( $users as $user ) {
            // Only display public user information
            $temp_users[$user->id]['name'] = $user->name;
            $temp_users[$user->id]['facebook_id'] = $user->facebook_id;
            $temp_users[$user->id]['score'] = number_format(round(floatval($user->rating), 2), 2);
        }

        $view = 'main';
        $my_rrequests = array();
        if ( $load_personal ) {
            $view = 'me';
            
            $my_rrequests = $this->rrequest->retrieve(
                array(
                    'user_id' => $this->wheelzo_user_id
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
                'rides' => $mapped_rides,
                'session' => $this->wheelzo_user_id,
                'session_url' => $this->facebook_url,
                'my_rrequests' => $my_rrequests,
                'rrequests' => $this->rrequest->retrieve_active(),
                'request_ride_id' => $this->input->get('ride'),
                'request_user_id' => $this->input->get('user')
            )
        );
    }
}