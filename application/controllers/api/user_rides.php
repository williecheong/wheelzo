<?php // if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class User_rides extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        // Autoloaded Config, Helpers, Models
        parse_str($_SERVER['QUERY_STRING'],$_REQUEST);
        $this->load->library('Facebook', 
            array(
                "appId" => FB_APPID, 
                "secret" => FB_SECRET
            )
        );
    }

    public function index_post() {
        if ( $this->session->userdata('user_id') ) {            
            $data = $this->post();

            $driver_id = $this->session->userdata('user_id');
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
                        $user_rides = $this->user_ride->retrieve(
                            array(
                                'id' => $user_ride_id
                            )
                        );
                        $user_ride = $user_rides[0];

                        $rides = $this->ride->retrieve(
                            array(
                                'id' => $ride_id
                            )
                        );

                        $ride = $rides[0];
                        if ( $ride->driver_id != $user_ride->user_id ) {
                            $driver = $this->user->retrieve(
                                array(
                                    'id' => $ride->driver_id
                                )
                            );

                            $passenger = $this->user->retrieve(
                                array(
                                    'id' => $user_ride->user_id
                                )
                            );

                            $fb_response = false;
                            try {
                                $fb_response = $this->facebook->api(
                                    '/' . $passenger[0]->facebook_id . '/notifications', 
                                    'POST', 
                                    array(
                                        'href' => '/fb', 
                                        'template' => '@[' . $driver[0]->facebook_id . '] added you to a ride on Wheelzo.',
                                        'access_token' => FB_APPID . '|' . FB_SECRET
                                    )
                                );
                            } catch ( Exception $e ) {
                                log_message('error', $e->getMessage() );
                            }
                            
                            if ( $fb_response ) {
                                echo json_encode(
                                    array(
                                        'status' => 'success',
                                        'message' => 'Passenger successfully posted. Passenger notified on Facebook.',
                                        'user_ride' => $user_ride
                                    )
                                );
                            } else {
                                echo json_encode(
                                    array(
                                        'status' => 'success',
                                        'message' => 'Passenger successfully posted. Passenger could not be notified on Facebook.',
                                        'user_ride' => $user_ride
                                    )
                                );   
                            }
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Passenger successfully posted.',
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
        if ( $this->session->userdata('user_id') ) {            
            $data = $this->put();

            $driver_id = $this->session->userdata('user_id');
            $user_ride_id = isset($data['user-rideID']) ? $data['user-rideID'] : '';
            $passenger_id = isset($data['passengerID']) ? $data['passengerID'] : '';
            
            if ( $this->_verify_driver_by_user_ride( $user_ride_id, $driver_id) ) {
                if ( $this->_verify_passenger_by_user_ride( $user_ride_id, $passenger_id) ) {
                        
                    $this->user_ride->update(
                        array(
                            'id' => $user_ride_id
                        ),  
                        array(  
                            'user_id' => $passenger_id
                        )
                    );

                    $user_rides = $this->user_ride->retrieve(
                        array(
                            'id' => $user_ride_id
                        )
                    );
                    $user_ride = $user_rides[0];

                    $rides = $this->ride->retrieve(
                        array(
                            'id' => $user_ride->ride_id
                        )
                    );
                    
                    $ride = $rides[0];
                    if ( $ride->driver_id != $user_ride->user_id ) {
                        $driver = $this->user->retrieve(
                            array(
                                'id' => $ride->driver_id
                            )
                        );

                        $passenger = $this->user->retrieve(
                            array(
                                'id' => $user_ride->user_id
                            )
                        );

                        $fb_response = false;
                        try {
                            $fb_response = $this->facebook->api(
                                '/' . $passenger[0]->facebook_id . '/notifications', 
                                'POST', 
                                array(
                                    'href' => base_url('me'), 
                                    'template' => '@[' . $driver[0]->facebook_id . '] added you to a ride on Wheelzo.',
                                    'access_token' => FB_APPID . '|' . FB_SECRET
                                )
                            );
                        } catch ( Exception $e ) {
                            log_message('error', $e->getMessage() );
                        }
                        
                        if ( $fb_response ) {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Passenger successfully updated. Passenger notified on Facebook.',
                                    'user_ride' => $user_ride
                                )
                            );
                        } else {
                            echo json_encode(
                                array(
                                    'status' => 'success',
                                    'message' => 'Passenger successfully updated. Passenger could not be notified on Facebook.',
                                    'user_ride' => $user_ride
                                )
                            );   
                        }
                    } else {
                        echo json_encode(
                            array(
                                'status' => 'success',
                                'message' => 'Passenger successfully updated.',
                                'user_ride' => $user_ride
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


    private function _verify_driver_by_user_ride( $user_ride_id, $user_id ) {
        $user_rides = $this->user_ride->retrieve(
            array(
                'id' => $user_ride_id
            )
        );

        if ( count($user_rides) > 0 ) {
            $user_ride = $user_rides[0];
            return $this->_verify_driver($user_ride->ride_id, $user_id);
        }

        return false;
    }

    private function _verify_passenger_by_user_ride( $user_ride_id, $user_id ) {
        $user_rides = $this->user_ride->retrieve(
            array(
                'id' => $user_ride_id
            )
        );

        if ( count($user_rides) > 0 ) {
            $user_ride = $user_rides[0];
            return $this->_verify_passenger($user_ride->ride_id, $user_id);
        }

        return false;
    }

    private function _verify_driver( $ride_id, $user_id ) {
        $rides = $this->ride->retrieve(
            array(
                'id' => $ride_id
            )
        );

        if ( count($rides) > 0 ) {
            $ride = $rides[0];
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
        $rides = $this->ride->retrieve(
            array(
                'id' => $ride_id
            )
        );

        if ( count($rides) > 0 ) {
            $ride = $rides[0];
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
