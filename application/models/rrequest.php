<?php

class rrequest extends CI_Model{
    
    function add_invitation( $rrequest_id = 0, $ride_id = 0 ) {
        $rrequest = $this->rrequest->retrieve_by_id( $rrequest_id ); 

        if ( $rrequest ) {
            $invitations = explode(WHEELZO_DELIMITER, $rrequest->invitations);
            
            foreach( $invitations as $invitation ) {
                if ( $invitation == $ride_id ) {
                    // request has received invite from this ride before
                    return false;
                }
            }

            // request has not received invite from this ride before
            if ( $ride_id != 0 ) {
                $invitations[] = $ride_id;
            }

            $this->rrequest->update(
                array(
                    'id' => $rrequest_id
                ),
                array(
                    'invitations' => implode(WHEELZO_DELIMITER, $invitations)
                )
            );

            return true;
        } else {
            // rrequest does not exist
            return false;
        }
    }

    function retrieve_active() {
        $user_id = $this->wheelzo_user_id;
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
        $user_id = $this->wheelzo_user_id;
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
        
        $rrequests = $query->result();
        if (count($rrequests) > 0) {
            foreach ($rrequests as $key => $rrequest) {
                $user = $this->user->retrieve_by_id($rrequest->user_id);
                if ($user) {
                    $rrequests[$key]->user_name = $user->name; 
                    $rrequests[$key]->user_facebook_id = $user->facebook_id; 
                    $rrequests[$key]->user_score = number_format(round(floatval($user->rating), 2), 2);
                }
            }
        }
        return $rrequests;
    }
    
    function retrieve_like( $data = array() ){
        $this->db->like($data);
        $query = $this->db->get('rrequest');
        
        $rrequests = $query->result();
        if (count($rrequests) > 0) {
            foreach ($rrequests as $key => $rrequest) {
                $user = $this->user->retrieve_by_id($rrequest->user_id);
                if ($user) {
                    $rrequests[$key]->user_name = $user->name; 
                    $rrequests[$key]->user_facebook_id = $user->facebook_id; 
                    $rrequests[$key]->user_score = number_format(round(floatval($user->rating), 2), 2);
                }
            }
        }
        return $rrequests;
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