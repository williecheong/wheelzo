<?php

class riderequest extends CI_Model{
    
    function retrieve_active() {
        $user_id = $this->session->userdata('user_id');
        $current = date( 'Y-m-d H:i:s', strtotime('today midnight') );
        $conditions = "`start`>'".$current."'";
        $riderequests = $this->riderequest->retrieve( $conditions );
        
        // Use request ID as the index key
        $temp_riderequests = array();
        foreach( $riderequests as $riderequest ) { 
            $temp_riderequests[$riderequest->id] = $riderequest;
        }

        return $temp_riderequests;
    }

    function retrieve_personal() {
        $user_id = $this->session->userdata('user_id');
        if ( $user_id ) {
            $riderequests = $this->riderequest->retrieve(
                array(
                    'user_id' => $user_id
                )
            );
            
            // Use request ID as the index key
            $temp_riderequests = array();
            foreach( $riderequests as $riderequest ) { 
                $temp_riderequests[$riderequest->id] = $riderequest; 
                
            }
            
            return $temp_riderequests;        
        
        } else {
            return array();
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->riderequest->retrieve(
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
        $this->db->insert('riderequest', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('riderequest');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('riderequest', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('riderequest');
    }

}

?>