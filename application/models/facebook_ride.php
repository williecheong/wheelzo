<?php

class facebook_ride extends CI_Model{
    
    function retrieve_by_ride( $ride_id = 0 ) {
        $objects = $this->facebook_ride->retrieve(
            array(
                'ride_id' => $ride_id
            )
        );

        if ( count($objects) > 0 ) {
            return $objects[0];
        } else {
            return false;
        }
    }

    function retrieve_by_fb( $facebook_post_id = 0 ) {
        $objects = $this->facebook_ride->retrieve(
            array(
                'facebook_post_id' => $facebook_post_id
            )
        );

        if ( count($objects) > 0 ) {
            return $objects[0];
        } else {
            return false;
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->facebook_ride->retrieve(
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
        $this->db->insert('facebook_ride', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('facebook_ride');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('facebook_ride', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('facebook_ride');
    }

}

?>