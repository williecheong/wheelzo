<?php

class review extends CI_Model{
    
    function retrieve_by_id( $id = 0 ) {
        $objects = $this->review->retrieve(
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
        $this->db->insert('review', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $this->db->order_by('last_updated', 'desc');
        $query = $this->db->get('review');
        
        $reviews = $query->result();
        if (count($reviews) > 0) {
           foreach ($reviews as $key => $review) {
                $user = $this->user->retrieve_by_id($review->giver_id);
                if ($user) {
                    $reviews[$key]->giver_name = $user->name;
                    $reviews[$key]->giver_facebook_id = $user->facebook_id;
                }
            } 
        }
        return $reviews;
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('review', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('review');
    }

}

?>