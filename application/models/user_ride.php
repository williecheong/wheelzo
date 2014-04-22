<?php

class user_ride extends CI_Model{
    
    function retrieve_ride_id( $data = array() ) {
        $this->db->select('ride_id');
        $this->db->where($data);
        $query = $this->db->get('user_ride');
        return $query->result();
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->user_ride->retrieve(
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
        $this->db->insert('user_ride', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('user_ride');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('user_ride', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('user_ride');
    }

}

?>