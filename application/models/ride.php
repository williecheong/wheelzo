<?php

class ride extends CI_Model{
    
    function retrieve_relevant() {
        $rides_personal = $this->ride->retrieve_personal();
        $rides_active = $this->ride->retrieve_active();

        // Order matters in this function.
        // We want to preserve elements in personal rides.
        $rides = $rides_personal + $rides_active;
        foreach( $rides as $id => $ride ) { 
            if ( !isset($ride->is_personal) ) {
                $rides[$id]->is_personal = false ;
            }

            if ( $ride->drop_offs == '' ) {
                $rides[$id]->drop_offs = array() ;
            } else {
                $rides[$id]->drop_offs = explode(WHEELZO_DELIMITER, $ride->drop_offs) ; 
            }
         
            $rides[$id]->passengers = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );

            $rides[$ride->id]->comments = $this->comment->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );
        }

        return $rides;
    }

    function retrieve_active() {
        $current = date( 'Y-m-d H:i:s', strtotime('today midnight') );
        $conditions = "`start`>'".$current."'";
        $rides = $this->ride->retrieve( $conditions );
        
        // Use ride ID as the index key
        $temp_rides = array();
        foreach( $rides as $ride ) { 
            $temp_rides[$ride->id] = $ride; 
        }

        return $temp_rides;
    }

    function retrieve_personal() {
        $user_id = $this->session->userdata('user_id');
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
            
            // Use ride ID as the index key
            $temp_rides = array();
            foreach( $rides as $ride ) { 
                $temp_rides[$ride->id] = $ride; 
                $temp_rides[$ride->id]->is_personal = true;
            }
            
            return $temp_rides;        
        
        } else {
            return array();
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
        return $query->result();
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