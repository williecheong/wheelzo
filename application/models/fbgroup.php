<?php

class fbgroup extends CI_Model{
    
    function retrieve_by_fb( $facebook_id = 0 ) {
        $objects = $this->fbgroup->retrieve(
            array(
                'facebook_id' => $facebook_id
            )
        );

        if ( count($objects) > 0 ) {
            return $objects[0];
        } else {
            return false;
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->fbgroup->retrieve(
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
        $this->db->insert('fbgroup', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('fbgroup');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('fbgroup', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('fbgroup');
    }

}

?>