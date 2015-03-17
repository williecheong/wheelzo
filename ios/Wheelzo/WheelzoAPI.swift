//
//  WheelzoAPI.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-01-09.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

protocol WheelzoAPIProtocol {
    func didRecieveResponse(results: NSArray)
}


class WheelzoAPI: NSObject {
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
            options:NSJSONReadingOptions.MutableContainers, error: nil) as NSArray
        
        delegate?.didRecieveResponse(jsonResult)
    }
    
    func searchItunesFor(searchTerm: String) {
        
        //Clean up the search terms by replacing spaces with +
        var itunesSearchTerm = searchTerm.stringByReplacingOccurrencesOfString(" ",
            withString: "+", options: NSStringCompareOptions.CaseInsensitiveSearch,
            range: nil)
        
        var escapedSearchTerm = itunesSearchTerm.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)
        
        //var urlPath = "https://itunes.apple.com/search?term=(escapedSearchTerm)&media=music"
        var urlPath = "http://staging.wheelzo.com/api/v2/users"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Access Wheelzo API at URL \(url)")
        
        connection.start()
    }
    
    // user stuff
    
    func getAllUsers() {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/users"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    func getMe() {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/users/me"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    func getUserFromUserId(userId: int_fast64_t) {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/users?id=\(userId)"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    func getUserFromFbId(fbId: int_fast64_t) {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/users?id=\(fbId)"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    // ride stuff
    
    func getCurrentRides() {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/rides"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    func getMyRides() {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/rides/me"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    func postRide(ride: RideModel) {
        
        println("postRide unsupported")
        
    }
    
    func deleteRide(rideId: int_fast64_t) {
        
        println("deleteRide unsupported")
        
    }
    
    // comment stuff
    
    func postComment(comment: CommentModel) {
        
        println("postComment unsupported")
        
    }
    
    func getComment(rideId: int_fast64_t) {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/comments?ride_id=\(rideId)"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self,startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
        
    }
    
    // feedback stuff
    
    func postFeedback() {
        
        println("postFeedback unsupported")
        
    }
    
    
    
    
}



