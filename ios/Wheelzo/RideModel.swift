//
//  RideModel.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-11.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation


class RideModel {

    var id: int_fast64_t;
    var driverId: int_fast64_t; // user.id that created this ride

    var origin: String;
    var destination: String;

    var capacity: int_fast64_t;
    var price: int_fast64_t;

    var start: String;

    var dropOffs: [String]?; // dropOffs is optional
    
    var comments = [CommentModel]();

    // todo: add dropOffs to the constructor
    
    init(id: int_fast64_t, driverId: int_fast64_t, origin: String, destination: String, capacity: int_fast64_t, price: int_fast64_t, start: String) {
        
        self.id = id;
        self.driverId = driverId;
        self.origin = origin;
        self.destination = destination;
        self.capacity = capacity;
        self.price = price;
        self.start = start;
        
    }
    
    
    func addComment(comment: CommentModel) {
        // actually might be unncecesary since we only have to pull the comments from the web and and then add the throught the api
        
        // adds a comment to the array
        self.comments.append(comment);
    }
    
}