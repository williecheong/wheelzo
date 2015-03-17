//
//  UserModel.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-11.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

// class that mirrors the User objects in the Wheelzo db

import Foundation

class UserModel {

    var id: int_fast64_t;
    var name: String;
    var facebookId: int_fast64_t;
    var cellNumber: int_fast64_t?; // currently unused


    // todo add cell number support

    init(id: int_fast64_t, name: String, facebookId: int_fast64_t) {
        
        self.id = id;
        self.name = name;
        self.facebookId = facebookId;
        
    }
    
}