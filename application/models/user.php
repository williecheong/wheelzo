<?php

class user extends CI_Model{
    
    function update_rating( $point_id = 0 ) {
        $point = $this->point->retrieve_by_id( $point_id );

        if ( $point ) {
            $receiver = $this->user->retrieve_by_id( $point->receiver_id );
            $current_rating = floatval( $receiver->rating );

            // Super secret score update algorithm
            $increment = $this->point->calculate_increment( $point->giver_id, $point->receiver_id );

            $this->user->update(
                array(
                    'id' => $receiver->id
                ),
                array(
                    'rating' => strval( round( $current_rating + $increment, 4 ) )
                )
            );

            return true;

        } else {
            return false;
        }
    }
    
    function to_notify( $user_id = 0, $type = '' ) {
        $user = $this->user->retrieve_by_id( $user_id ); 

        if ( $user ) {
            $notifications = explode(WHEELZO_DELIMITER, $user->notifications);
            
            foreach( $notifications as $notification ) {
                if ( $notification == $type ) {
                    // user has been notified about this before
                    return false;
                }
            }

            // user was not notified about this before
            if ( $type != '' ) {
                $notifications[] = $type;
            }

            $this->user->update(
                array(
                    'id' => $user_id
                ),
                array(
                    'notifications' => implode(WHEELZO_DELIMITER, $notifications)
                )
            );

            return true;
        } else {
            // user does not exist
            return null;
        }
    }

    function try_register( $facebook_id = 0 ) {
        $availability = $this->user->retrieve(
            array(
                'facebook_id' => $facebook_id
            )
        );
        
        if ( count($availability) > 0 ) {
            // user exists, 
            // update image and name
            // update last_active for last login
            $facebook_profile = $this->facebook->api('/me');
            $this->user->update(
                array(
                    'id' => $availability[0]->id
                ),
                array(
                    'name' =>$facebook_profile['name'],
                    'notifications' => '',
                    'last_login' => date( 'Y-m-d H:i:s' )
                )
            );

            return $availability[0];

        } else {
            // user does not exist yet.
            // put this facebook person inside our database
            $facebook_profile = $this->facebook->api('/me');
            $user_id = $this->user->create(
                array(
                    'name' => $facebook_profile['name'],
                    'email' => ( isset($facebook_profile['email']) ) ? $facebook_profile['email'] : '',
                    'facebook_id' => $facebook_id,
                    'cell_number' => '',
                    'rating' => '',
                    'notifications' => '',
                    'last_login' => date( 'Y-m-d H:i:s' )
                )
            );

            $user = $this->user->retrieve_by_id( $user_id );

            return $user;
        }
    }

    function retrieve_by_id( $id = 0 ) {
        $objects = $this->user->retrieve(
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