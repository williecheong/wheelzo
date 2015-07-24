//
//  WheeloCommentAPI.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-07-06.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

protocol WheelzoCommentAPIProtocol {
    func didRecieveCommentResponse(results: NSArray)
}

class WheelzoCommentAPI: NSObject {
    // this api is used exclusively for loading and posting comments (probably only used in detail view)
    
    var data: NSMutableData = NSMutableData()
    var delegate: WheelzoCommentAPIProtocol?
    
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
        
        delegate?.didRecieveCommentResponse(jsonResult)
    }
    
    // comment stuff
    
    func postComment(commentText: String, rideId: Int, userId: Int, callback: ()->Void ) {
        
        // synchronous
        
        // does not use the connection flow, so no delegate
        
        // only works if you are logged in
        var token = FBSDKAccessToken.currentAccessToken().tokenString
        
        var urlPath = "http://staging.wheelzo.com/api/v2/comments"
        var url: NSURL! = NSURL(string: urlPath)
        
        let request = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "POST"
        let postString = "{\"comment\": \"\(commentText)\",\"rideID\": \"\(rideId)\"}"
        
        println(postString)
        
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
            
            //println("response = \(response)")
            
            let responseString = NSString(data: data, encoding: NSUTF8StringEncoding)
            println("responseString = \(responseString)")            
        }
        
        task.resume();
        callback();
    }
    
    func getComments(rideId: Int) {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/comments?ride_id=\(rideId)"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
            delegate: self, startImmediately: false)
        
        println("Requesting: \(urlPath)")
        
        connection.start()
    }
    
    
}
