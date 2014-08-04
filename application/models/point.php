<?php

class point extends CI_Model{
    
    function calculate_increment( $giver_id = 0, $receiver_id = 0 ) {
        $given_points = $this->point->retrieve(
            array(
                'giver_id' => $giver_id,
                'receiver_id' => $receiver_id
            )
        );

        // Convergence to 3.33. 
        // Geometric series? idk...
        return pow( 0.7, ( count($given_points) - 1) );
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->point->retrieve(
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
        $this->db->insert('point', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $this->db->order_by('last_updated', 'asc');
        $query = $this->db->get('point');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('point', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('point');
    }

}

?>