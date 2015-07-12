<?php

class comment extends CI_Model{
    
    function retrieve_by_id( $id = 0 ) {
        $objects = $this->comment->retrieve(
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
        $this->db->insert('comment', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $this->db->order_by('last_updated', 'asc');
        $query = $this->db->get('comment');
        $comments = $query->result();
        if (count($comments) > 0) {
           foreach ($comments as $key => $ride) {
                $user = $this->user->retrieve_by_id($ride->user_id);
                if ($user) {
                    $comments[$key]->user_facebook_id = $user->facebook_id;
                }
            } 
        }
        return $comments;
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('comment', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('comment');
    }

}

?>