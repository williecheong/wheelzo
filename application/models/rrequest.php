<?php

class rrequest extends CI_Model{
    
    function retrieve_active() {
        $user_id = $this->session->userdata('user_id');
        $current = date( 'Y-m-d H:i:s', strtotime('today midnight') );
        $conditions = "`start`>'".$current."'";
        $rrequests = $this->rrequest->retrieve( $conditions );
        
        // Use request ID as the index key
        $temp_rrequests = array();
        foreach( $rrequests as $rrequest ) { 
            $temp_rrequests[$rrequest->id] = $rrequest;
        }

        return $temp_rrequests;
    }

    function retrieve_personal() {
        $user_id = $this->session->userdata('user_id');
        if ( $user_id ) {
            $rrequests = $this->rrequest->retrieve(
                array(
                    'user_id' => $user_id
                )
            );
            
            // Use request ID as the index key
            $temp_rrequests = array();
            foreach( $rrequests as $rrequest ) { 
                $temp_rrequests[$rrequest->id] = $rrequest; 
                
            }
            
            return $temp_rrequests;        
        
        } else {
            return array();
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->rrequest->retrieve(
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
        $this->db->insert('rrequest', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('rrequest');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('rrequest', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('rrequest');
    }

}

?>