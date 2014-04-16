<?php

class ride extends CI_Model{
    
    function retrieve_active() {
        // Use ride ID as the index key
        $temp_rides = array();
        $rides = $this->ride->retrieve();
        foreach( $rides as $ride ) { 
            $temp_rides[$ride->id] = $ride; 
            $temp_rides[$ride->id]->drop_offs = ($ride->drop_offs=='') ? array() : explode(WHEELZO_DELIMITER, $ride->drop_offs) ; 

            $temp_rides[$ride->id]->passengers = $this->user_ride->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );
            $temp_rides[$ride->id]->comments = $this->comment->retrieve(
                array(
                    'ride_id' => $ride->id 
                )
            );
        }
        return $temp_rides;
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