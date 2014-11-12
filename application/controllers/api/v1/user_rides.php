<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/API_Controller.php');

class User_rides extends API_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
    }

    public function index_post() {
        if ( $this->wheelzo_user_id ) {            
            $data = clean_input( $this->post() );

            $driver_id = $this->wheelzo_user_id;
            $ride_id = isset($data['rideID']) ? $data['rideID'] : '';
            $passenger_id = isset($data['passengerID']) ? $data['passengerID'] : '';

            if ( $this->_verify_driver( $ride_id, $driver_id) ) {
                if ( $this->_verify_passenger( $ride_id, $passenger_id) ) {
                    if ( $this->_verify_capacity($ride_id) ) {
                        
                        $user_ride_id = $this->user_ride->create(  
                            array(  
                                'user_id' => $passenger_id,
                                'ride_id' => $ride_id
                            )
                        );

                        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );
                        $ride = $this->ride->retrieve_by_id( $ride_id );
                        
                        if ( $ride->driver_id != $user_ride->user_id ) {
                            $driver = $this->user->retrieve_by_id( $ride->driver_id );
                            $passenger = $this->user->retrieve_by_id( $user_ride->user_id );

                            $notification_type = $ride->id . NOTIFY_ASSIGNED; 
                            $to_notify = $this->user->to_notify( $passenger->id, $notification_type );
            
                            if ( $to_notify ) {
                                $fb_response = false;
                                if ( ENVIRONMENT == 'production' || in_array($passenger->facebook_id, unserialize(WHEELZO_ADMINS)) ) {
                                    try {
                                        $fb_response = $this->facebook->api(
                                            '/' . $passenger->facebook_id . '/notifications', 
                                            'POST', 
                                            array(
                                                'href' => '/fb?goto='.$ride->id, 
                                                'template' => '@[' . $driver->facebook_id . '] added you to a ride scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
                                                'access_token' => FB_APPID . '|' . FB_SECRET
                                            )
                                        );
                                    } catch ( Exception $e ) {
                                        log_message('error', $e->getMessage() );
                                    }
                                }

                                if ( $fb_response ) {
                                    echo json_encode(
                                        array(
                                            'status' => 'success',
                                            'message' => $passenger->name.' successfully added and notified on Facebook.',
                                            'user_ride' => $user_ride
                                        )
                                    );
                                } else {
                                    echo json_encode(
                                        array(
                                            'status' => 'success',
                                            'message' => $passenger->name.' successfully added but could not be notified on Facebook.',
                                            'user_ride' => $user_ride
                                        )
                                    );   
                                }
                            } else {
                                echo json_encode(
                                    array(
                                        'status' => 'success',
                                        'message' => $passenger->name.' successfully added and already notified on Facebook.',
                                        'user_ride' => $user_ride
                                    )
                                );
                            }
                            
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'You were successfully added to your own ride.',
                                    'user_ride' => $user_ride
                                )
                            );
                        }
                    } else {
                        echo json_encode(
                            array(
                                'status' => 'fail',
                                'message' => 'The capacity of this ride is reached. Stop cheating.'
                            )
                        );
                    }
                } else {
                    echo json_encode(
                        array(
                            'status' => 'fail',
                            'message' => 'This person did not comment before. Stop cheating.'
                        )
                    );
                }
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'You are not the driver. Stop hacking.'
                    )
                );
            }    
        } else {
            echo json_encode(
                array(
                    'status' => 'fail',
                    'message' => 'User is not logged in.'
                )
            );
        }
        
        return;
    }

    public function index_put() {
        if ( $this->wheelzo_user_id ) {            
            $data = clean_input( $this->put() );

            $driver_id = $this->wheelzo_user_id;
            $user_ride_id = isset($data['user-rideID']) ? $data['user-rideID'] : '';
            $passenger_id = isset($data['passengerID']) ? $data['passengerID'] : '';
            
            if ( $this->_verify_driver_by_user_ride( $user_ride_id, $driver_id) ) {
                if ( $this->_verify_passenger_by_user_ride( $user_ride_id, $passenger_id) ) {
                    
                    $old_user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );

                    if ( $old_user_ride->user_id != $passenger_id ) {
                        $this->user_ride->update(
                            array(
                                'id' => $user_ride_id
                            ),  
                            array(  
                                'user_id' => $passenger_id
                            )
                        );

                        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );
                        $ride = $this->ride->retrieve_by_id( $user_ride->ride_id );
                        $driver = $this->user->retrieve_by_id( $ride->driver_id );
                        
                        $output = array(
                            'status' => 'success',
                            'message' => '',
                            'user_ride' => $user_ride
                        );

                        if ( $ride->driver_id != $old_user_ride->user_id ) {
                            $old_passenger = $this->user->retrieve_by_id( $old_user_ride->user_id );
                            
                            $notification_type = $ride->id . NOTIFY_REMOVED; 
                            $to_notify = $this->user->to_notify( $old_passenger->id, $notification_type );
            
                            if ( $to_notify ) {
                                $fb_response_to_old = false;
                                if ( ENVIRONMENT == 'production' || in_array($old_passenger->facebook_id, unserialize(WHEELZO_ADMINS)) ) {
                                    try {
                                        $fb_response_to_old = $this->facebook->api(
                                            '/' . $old_passenger->facebook_id . '/notifications', 
                                            'POST', 
                                            array(
                                                'href' => '/fb?goto='.$ride->id, 
                                                'template' => '@[' . $driver->facebook_id . '] removed you from a ride scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
                                                'access_token' => FB_APPID . '|' . FB_SECRET
                                            )
                                        );
                                    } catch ( Exception $e ) {
                                        log_message('error', $e->getMessage() );
                                    }
                                }

                                if ( $fb_response_to_old ) {
                                    $output['message'] .= $old_passenger->name." successfully removed and notified on Facebook.";
                                } else {
                                    $output['message'] .= $old_passenger->name." successfully removed but could not be notified on Facebook.";
                                }
                            } else {
                                $output['message'] .= $old_passenger->name." successfully removed and already notified on Facebook.";
                            }
                        } else {
                            $output['message'] .= "You were successfully removed from your own ride.";
                        }

                        $output['message'] .= "\n";

                        if ( $ride->driver_id != $user_ride->user_id ) {
                            $new_passenger = $this->user->retrieve_by_id( $user_ride->user_id );
                            
                            $notification_type = $ride->id . NOTIFY_ASSIGNED; 
                            $to_notify = $this->user->to_notify( $new_passenger->id, $notification_type );
            
                            if ( $to_notify ) {                            
                                $fb_response_to_new = false;
                                if ( ENVIRONMENT == 'production' || in_array($new_passenger->facebook_id, unserialize(WHEELZO_ADMINS)) ) {
                                    try {
                                        $fb_response_to_new = $this->facebook->api(
                                            '/' . $new_passenger->facebook_id . '/notifications', 
                                            'POST', 
                                            array(
                                                'href' => '/fb?goto='.$ride->id, 
                                                'template' => '@[' . $driver->facebook_id . '] added you to a ride scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
                                                'access_token' => FB_APPID . '|' . FB_SECRET
                                            )
                                        );
                                    } catch ( Exception $e ) {
                                        log_message('error', $e->getMessage() );
                                    }
                                }

                                if ( $fb_response_to_new ) {
                                    $output['message'] .= $new_passenger->name." successfully added and notified on Facebook.";
                                } else {
                                    $output['message'] .= $new_passenger->name." successfully added but could not be notified on Facebook.";   
                                }
                            } else {
                                $output['message'] .= $new_passenger->name." successfully added and already notified on Facebook.";
                            }
                        } else {
                            $output['message'] .= "You were successfully added to your own ride.";
                        }

                        echo json_encode($output);
                    } else {
                        echo json_encode(
                            array(
                                'status' => 'fail',
                                'message' => 'This person is already assigned to this seat.'
                            )
                        );
                    }        
                } else {
                    echo json_encode(
                        array(
                            'status' => 'fail',
                            'message' => 'This person did not comment before. Stop cheating.'
                        )
                    );
                }
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'You are not the driver. Stop hacking.'
                    )
                );
            }    
        } else {
            echo json_encode(
                array(
                    'status' => 'fail',
                    'message' => 'User is not logged in.'
                )
            );
        }
        
        return;
    }

    public function index_delete( $user_ride_id = '' ) {
        if ( $this->wheelzo_user_id ) {            
            $driver_id = $this->wheelzo_user_id;
            
            if ( $this->_verify_driver_by_user_ride( $user_ride_id, $driver_id) ) {
                $old_user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );
                
                $this->user_ride->delete( 
                    array( 
                        'id' => $user_ride_id
                    )
                );

                $ride = $this->ride->retrieve_by_id( $old_user_ride->ride_id );
                
                if ( $old_user_ride->user_id != $driver_id ) {
                    $driver = $this->user->retrieve_by_id( $ride->driver_id );
                    $old_passenger = $this->user->retrieve_by_id( $old_user_ride->user_id );
                    
                    $notification_type = $ride->id . NOTIFY_REMOVED; 
                    $to_notify = $this->user->to_notify( $old_passenger->id, $notification_type );
                    
                    if ( $to_notify ) {        
                        $fb_response_to_old = false;
                        if ( ENVIRONMENT == 'production' || in_array($old_passenger->facebook_id, unserialize(WHEELZO_ADMINS)) ) {
                            try {
                                $fb_response_to_old = $this->facebook->api(
                                    '/' . $old_passenger->facebook_id . '/notifications', 
                                    'POST', 
                                    array(
                                        'href' => '/fb?goto='.$ride->id, 
                                        'template' => '@[' . $driver->facebook_id . '] removed you from a ride scheduled for '. date( 'l, M j', strtotime($ride->start) ) .'.',
                                        'access_token' => FB_APPID . '|' . FB_SECRET
                                    )
                                );
                            } catch ( Exception $e ) {
                                log_message('error', $e->getMessage() );
                            }
                        }

                        if ( $fb_response_to_old ) {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => $old_passenger->name.' successfully removed and notified on Facebook.',
                                    'ride_id' => $ride->id
                                )
                            );
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => $old_passenger->name.' successfully removed but could not be notified on Facebook.',
                                    'ride_id' => $ride->id
                                )
                            );
                        }
                    } else {
                        echo json_encode(
                            array(
                                'status' => 'success',
                                'message' => $old_passenger->name.' successfully removed and already notified on Facebook.',
                                'ride_id' => $ride->id
                            )
                        );
                    }
                } else {
                    echo json_encode(
                        array(
                            'status' => 'success',
                            'message' => 'You were successfully removed from your own ride.',
                            'ride_id' => $ride->id
                        )
                    );
                }
            } else {
                echo json_encode(
                    array(
                        'status' => 'fail',
                        'message' => 'You are not the driver. Stop hacking.'
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'status' => 'fail',
                    'message' => 'User is not logged in.'
                )
            );
        }    

        return;
    }


    private function _verify_driver_by_user_ride( $user_ride_id, $user_id ) {
        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );

        if ( $user_ride ) {
            return $this->_verify_driver($user_ride->ride_id, $user_id);
        }

        return false;
    }

    private function _verify_passenger_by_user_ride( $user_ride_id, $user_id ) {
        $user_ride = $this->user_ride->retrieve_by_id( $user_ride_id );

        if ( $user_ride ) {
            return $this->_verify_passenger($user_ride->ride_id, $user_id);
        }

        return false;
    }

    private function _verify_driver( $ride_id, $user_id ) {
        $ride = $this->ride->retrieve_by_id( $ride_id );

        if ( $ride ) {
            if ( $ride->driver_id == $user_id ) {
                return true;
            }
        }

        return false;    
    }

    private function _verify_passenger( $ride_id, $user_id ) {
        // Only people who have commented before can be a passenger
        $comments = $this->comment->retrieve(
            array(
                'ride_id' => $ride_id
            )
        );

        foreach ( $comments as $comment ) {
            if ( $comment->user_id == $user_id ) {
                return true;
            }
        }

        return false;
    }

    private function _verify_capacity( $ride_id ) {
        $ride = $this->ride->retrieve_by_id( $ride_id );

        if ( $ride ) {
            $user_rides = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride_id
                )
            );

            if ( count($user_rides) < $ride->capacity ) {
                return true;
            }

        }

        return false;
    }
}
