//
//  Threading.swift
//  SwiftThreading
//
//  Created by Joshua Smith on 7/5/14.
//  Copyright (c) 2014 iJoshSmith. All rights reserved.
//

//
// This code has been tested against Xcode 6 Beta 5.
//

//import Foundation
//
//infix operator ~> {}

/**
Executes the lefthand closure on a background thread and,
upon completion, the righthand closure on the main thread.
*/

//func ~> (
//    backgroundClosure: () -> Void,
//    mainClosure:       () -> Void)
//{
//    dispatch_async(queue) {
//        backgroundClosure()
//        dispatch_async(dispatch_get_main_queue(), mainClosure)
//    }
//}

/**
Executes the lefthand closure on a background thread and,
upon completion, the righthand closure on the main thread.
Passes the background closure's output to the main closure.
*/

//func ~> <T> (
//    backgroundClosure: () -> T,
//    mainClosure:       (result: T) -> ())
//{
//    dispatch_async(queue) {
//        let result = backgroundClosure()
//        dispatch_async(dispatch_get_main_queue(), {
//            mainClosure(result: result)
//        })
//    }
//}

/** Serial dispatch queue used by the ~> operator. */
//private let queue = dispatch_queue_create("serial-worker", DISPATCH_QUEUE_SERIAL)