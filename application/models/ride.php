<?php

class ride extends CI_Model{
    
    function retrieve_relevant() {
        $rides_personal = $this->ride->retrieve_personal();
        $rides_active = $this->ride->retrieve_active();

        // Order matters in this function.
        // We want to preserve elements in personal rides.
        $rides = $rides_personal + $rides_active;

        return $rides;
    }

    function retrieve_active() {
        $user_id = $this->wheelzo_user_id;
        $current = date( 'Y-m-d H:i:s', strtotime('today midnight') );
        $conditions = "`start`>'".$current."'";
        $rides = $this->ride->retrieve( $conditions );
        
        foreach( $rides as $key => $ride ) { 
            $rides[$key]->is_personal = false;
            
            if ( $ride->driver_id == $user_id ) {
                $rides[$key]->is_personal = true;
            } else {
                $passengers = $this->user_ride->retrieve(
                    array(
                        'ride_id' => $ride->id 
                    )
                );
                foreach ( $passengers as $passenger ) {
                    if ($passenger->user_id == $user_id ) {
                        $rides[$key]->is_personal = true;
                        break;
                    }
                }
            }
        }

        return $rides;
    }

    function retrieve_personal() {
        $user_id = $this->wheelzo_user_id;
        if ( $user_id ) {
            $conditions = '`driver_id` = '.$user_id;

            $passenger_of_rides = $this->user_ride->retrieve_ride_id(
                array(
                    'user_id' => $user_id
                )
            );

            foreach ( $passenger_of_rides as $mapping ) {
                $conditions .= " OR `id` = ".$mapping->ride_id;
            }

            $rides = $this->ride->retrieve($conditions);
            
            foreach( $rides as $key => $ride ) { 
                $rides[$key]->is_personal = true;
            }
            
            return $rides;        
        
        } else {
            return array();
        }
    }

    function retrieve_where_user_is_driver() {
        $user_id = $this->wheelzo_user_id;
        if ( $user_id ) {
            $conditions = '`driver_id` = '.$user_id;
            $rides = $this->ride->retrieve($conditions);
            foreach( $rides as $key => $ride ) { 
                $rides[$key]->is_personal = true;
            }
            
            return $rides;        
        
        } else {
            return array();
        }
    }

    function retrieve_where_user_is_passenger() {
        $user_id = $this->wheelzo_user_id;
        if ( $user_id ) {
            $conditions = array();
            $passenger_of_rides = $this->user_ride->retrieve_ride_id(
                array(
                    'user_id' => $user_id
                )
            );

            if (count($passenger_of_rides) == 0) {
                return array();
            }

            foreach ( $passenger_of_rides as $mapping ) {
                $conditions[] = "`id` = ".$mapping->ride_id;
            }

            $conditions = implode(" OR ", $conditions);
            $rides = $this->ride->retrieve($conditions);
            
            foreach( $rides as $key => $ride ) { 
                $rides[$key]->is_personal = true;
            }
            
            return $rides;        
        
        } else {
            return array();
        }
    }

    function retrieve_active_by_user( $user_id = 0 ) {
        $objects = $this->ride->retrieve(
            array(
                'driver_id' => $user_id,
                'start >' => date( 'Y-m-d H:i:s', strtotime('today midnight') )
            )
        );

        if ( count($objects) > 0 ) {
            return $objects;
        } else {
            return false;
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->ride->retrieve(
            array(
                'id' => $id
            )
        );

        if ( count($objects) > 0 ) {
            return $objects[0];
        } else {
            return false;
        }
    }

    // BEGIN BASIC CRUD FUNCTIONALITY

    function create( $data = array() ){
        $this->db->insert('ride', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('ride');
        
        $rides = $query->result();
        if (count($rides) > 0) {
           foreach ($rides as $key => $ride) {
                $drop_offs = array();
                if ( strlen($ride->drop_offs) > 0 ) {
                    $drop_offs = explode(WHEELZO_DELIMITER, $ride->drop_offs) ; 
                }
                $rides[$key]->drop_offs = $drop_offs;

                $user = $this->user->retrieve_by_id($ride->driver_id);
                if ($user) {
                    $rides[$key]->driver_name = $user->name;
                    $rides[$key]->driver_facebook_id = $user->facebook_id;
                }
            } 
        }
        return $rides;
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('ride', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('ride');
    }
}

?>