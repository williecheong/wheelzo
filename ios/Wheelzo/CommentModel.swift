//
//  CommentModel.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-11.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

class CommentModel {
    
    var id: int_fast64_t;
    
    var userId: int_fast64_t; // user.id that made the comment
    var rideId: int_fast64_t; // ride.id that comment is on
    
    var comment: String; // the actual text
    
    
    init(id: int_fast64_t, userId: int_fast64_t, rideId: int_fast64_t, comment: String) {
        
        self.id = id;
        self.userId = userId;
        self.rideId = rideId;
        self.comment = comment;
        
    }
    
}