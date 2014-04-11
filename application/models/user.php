<?php

class user extends CI_Model{
    
    function try_register( $facebook_id = 0 ) {
        $availability = $this->user->retrieve(
            array(
                'facebook_id' => $facebook_id
            )
        );
        
        if ( count($availability) > 0 ) {
            // user exists, nothing to do
            return $availability[0]->id;

        } else {
            // user does not exist yet.
            // put this facebook person inside our database
            $facebook_profile = $this->facebook->api('/me');
            
            $user_id = $this->user->create(
                array(
                    'name' => $facebook_profile['name'],
                    'email' => $facebook_profile['email'],
                    'facebook_id' => $facebook_id,
                    'cell_number' => ''
                )
            );

            return $user_id;
        }
    }

    // BEGIN BASIC CRUD FUNCTIONALITY

    function create( $data = array() ){
        $this->db->insert('user', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('user');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('user', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('user');
    }

}

?>