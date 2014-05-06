<?php

class request extends CI_Model{
    
    function retrieve_active() {
        $user_id = $this->session->userdata('user_id');
        $current = date( 'Y-m-d H:i:s', strtotime('today midnight') );
        $conditions = "`start`>'".$current."'";
        $requests = $this->request->retrieve( $conditions );
        

        // Use request ID as the index key
        $temp_requests = array();
        foreach( $requests as $request ) { 
            $temp_requests[$request->id] = $request;
        }

        return $temp_requests;
    }

    function retrieve_personal() {
        $user_id = $this->session->userdata('user_id');
        if ( $user_id ) {
            $requests = $this->request->retrieve($conditions);
            
            // Use request ID as the index key
            $temp_requests = array();
            foreach( $requests as $request ) { 
                $temp_requests[$request->id] = $request; 
                
            }
            
            return $temp_requests;        
        
        } else {
            return array();
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->request->retrieve(
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
        $this->db->insert('request', $data);    
        return $this->db->insert_id();
    }

    function retrieve( $data = array() ){
        $this->db->where($data);
        $query = $this->db->get('request');
        return $query->result();
    }
    
    function update( $criteria = array(), $new_data = array() ){
        $this->db->where($criteria);
        $this->db->update('request', $new_data);
    }
    
    function delete( $data = array() ){
        $this->db->where($data);
        $this->db->delete('request');
    }

}

?>