//
//  WheelzoAPI.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-01-09.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit



protocol WheelzoAPIProtocol {
    func didRecieveRideResponse(results: NSArray)
    func didRecieveUserResponse(results: NSArray)
    func didRecieveReviewsResponse(results: NSArray)
}


class WheelzoAPI: NSObject {
    // API for anything non-comment related
    
    static let stagingPrefix = "http://staging."
    static let productionPrefix = "https://"
    
    let urlPrefix = (SettingsViewController.useProduction ? productionPrefix : stagingPrefix)

    var data: NSMutableData = NSMutableData()
    var delegate: WheelzoAPIProtocol?
    
    //NSURLConnection Connection failed
    func connection(connection: NSURLConnection!, didFailWithError error: NSError!) {
        println("Failed with error:\(error.localizedDescription)")
    }
    
    //New request so we need to clear the data object
    func connection(didReceiveResponse: NSURLConnection!, didReceiveResponse response:NSURLResponse!) {
        self.data = NSMutableData()
    }
    
    //Append incoming data
    func connection(connection: NSURLConnection!, didReceiveData data: NSData!) {
        self.data.appendData(data)
    }
    
    //NSURLConnection delegate function
    func connectionDidFinishLoading(connection: NSURLConnection!) {
        //Finished receiving data and convert it to a JSON object
        
        //var jsonResult: NSDictionary = NSJSONSerialization.JSONObjectWithData(data,
        //    options:NSJSONReadingOptions.MutableContainers, error: nil) as NSDictionary
        
        //Finished receiving data and convert it to a JSON object
        // Should use NSObject data and then figure out if it is array or dictionary (for versatility)
        var jsonResult: NSArray = NSJSONSerialization.JSONObjectWithData(data,
            options:NSJSONReadingOptions.MutableContainers, error: nil) as! NSArray
        
        
        if (connection.currentRequest.URL?.lastPathComponent == "rides") {
            
            println("delegating to rides")
            
            // if querying for rides use ride delegate
            
            delegate?.didRecieveRideResponse(jsonResult)

            
        } else if (connection.currentRequest.URL?.lastPathComponent == "users") {
            
            println("delegating to users")
            
            // otherise we are querying for users (need to find a more future-proof way to do this
            
            delegate?.didRecieveUserResponse(jsonResult)

        } else if (connection.currentRequest.URL?.lastPathComponent == "reviews") {
            
            println("delegating to reviews")
            
            // otherise we are querying for users (need to find a more future-proof way to do this
            
            delegate?.didRecieveReviewsResponse(jsonResult)
            
        }
        
    }
    
    // user stuff
        
    func getUserFromUserId(userId: Int) {
        
        var urlPath = "\(urlPrefix)wheelzo.com/api/v2/users?id=\(userId)"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }

    func getUserFromFbId(fbId: Int) {
        
        var urlPath = "\(urlPrefix)wheelzo.com/api/v2/users?facebook_id=\(fbId)"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    func syncGetUserDataFromUserId(userId: String) -> NSDictionary {
        
        let urlPath = "\(urlPrefix).wheelzo.com/api/v2/users?id=\(userId)"
        let url: NSURL = NSURL(string: urlPath)!
        let request1: NSURLRequest = NSURLRequest(URL: url)
        
        var response: AutoreleasingUnsafeMutablePointer<NSURLResponse? >= nil
        var error: NSErrorPointer = nil
        var dataVal: NSData =  NSURLConnection.sendSynchronousRequest(request1, returningResponse: response, error:nil)!
        var err: NSError
        
        
        var userArray: NSArray = NSJSONSerialization.JSONObjectWithData(dataVal, options: NSJSONReadingOptions.MutableContainers, error: nil) as! NSArray
        
        let userData = userArray.firstObject as! NSDictionary
        
        return userData;
        
    }
    
    func syncGetFbIdFromUserId(userId: String) -> String {
        
        let urlPath = "\(urlPrefix)wheelzo.com/api/v2/users?id=\(userId)"
        let url: NSURL = NSURL(string: urlPath)!
        let request1: NSURLRequest = NSURLRequest(URL: url)
        
        var response: AutoreleasingUnsafeMutablePointer<NSURLResponse? >= nil
        var error: NSErrorPointer = nil
        var dataVal: NSData =  NSURLConnection.sendSynchronousRequest(request1, returningResponse: response, error:nil)!
        var err: NSError
        
        
        var userArray: NSArray = NSJSONSerialization.JSONObjectWithData(dataVal, options: NSJSONReadingOptions.MutableContainers, error: nil) as! NSArray

        let userData = userArray.firstObject as! NSDictionary
        
        let fbid = userData["facebook_id"] as! String;
        
        println("got data \(fbid)")
        
        return userData["facebook_id"] as! String;
        
    }
    
    // review stuff
    
    func getReviews(userId: String) {
        
        let urlPath = "\(urlPrefix)wheelzo.com/api/v2/reviews?receiver_id=\(userId)"
        let url: NSURL! = NSURL(string: urlPath)
        let request = NSMutableURLRequest(URL: url)
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        
        let connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self, startImmediately: false)
        
        println("requesting reviews: \(urlPath)")
        
        connection.start()
    }
    
    // ride stuff
    
    func getCurrentRides() {
        
        let urlPath = "\(urlPrefix)wheelzo.com/api/v2/rides"
        let url: NSURL! = NSURL(string: urlPath)
        let request = NSMutableURLRequest(URL: url)
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")

        let connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self, startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
//    func getMyRides() {
//        
//        var urlPath = "http://staging.wheelzo.com/api/v2/rides/me"
//        var url: NSURL! = NSURL(string: urlPath)
//        var request: NSURLRequest = NSURLRequest(URL: url)
//        var connection: NSURLConnection! = NSURLConnection(request: request,
//            delegate: self,startImmediately: false)
//        
//        println("Requesting: \(urlPath)")
//        
//        connection.start()
//    }
    
    func postRide(origin: String, destination: String, capacity: Int, price: Int, departureDate: String, departureTime: String) {
        
        println("posting a ride...");
        
        
        //posts a ride
        
        var token = FBSDKAccessToken.currentAccessToken().tokenString
        
        var urlPath = "\(urlPrefix)wheelzo.com/api/v2/rides"
        var url: NSURL! = NSURL(string: urlPath)
        
        let request = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "POST"
        
        
        let postString = "{\"origin\": \"\(origin)\",\"destination\": \"\(destination)\",\"departureDate\":\"\(departureDate)\",\"departureTime\": \"\(departureTime)\",\"capacity\": \"\(capacity)\",\"price\": \"\(price)\"}"
        
        
        // config stuff
        request.HTTPBody = postString.dataUsingEncoding(NSUTF8StringEncoding, allowLossyConversion: false)
        let tokenHeader = "Fb-Wheelzo-Token"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        request.addValue(token, forHTTPHeaderField: "Fb-Wheelzo-Token")
        
        
        let task = NSURLSession.sharedSession().dataTaskWithRequest(request) {
            data, response, error in
            
            if error != nil {
                println("error=\(error)")
                return
            }
            
            println("response = \(response)")
            
            let responseString = NSString(data: data, encoding: NSUTF8StringEncoding)
            println("responseString = \(responseString)")
        }
        task.resume()
        
        //end of post ride

    }
    
    func deleteRide(rideId: Int) {
        
        println("attempting ride deletion")
        
        //posts a ride
        
        var token = FBSDKAccessToken.currentAccessToken().tokenString
        
        var urlPath = "\(urlPrefix)wheelzo.com/api/v2/rides/index/\(rideId)"
        var url: NSURL! = NSURL(string: urlPath)
        
        let request = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "DELETE"
        
       
        // config stuff
        let tokenHeader = "Fb-Wheelzo-Token"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        request.addValue(token, forHTTPHeaderField: "Fb-Wheelzo-Token")
        
        
        let task = NSURLSession.sharedSession().dataTaskWithRequest(request) {
            data, response, error in
            
            if error != nil {
                println("error=\(error)")
                return
            }
            
            println("response = \(response)")
            
            let responseString = NSString(data: data, encoding: NSUTF8StringEncoding)
            println("responseString = \(responseString)")
        }
        task.resume()
        
        // end of delete ride
        
    }
    
    // feedback stuff
    
    func postFeedback() {
        
        println("postFeedback unsupported")
        
    }
    
    
    
    
}



