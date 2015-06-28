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
            options:NSJSONReadingOptions.MutableContainers, error: nil) as! NSArray
        
        delegate?.didRecieveResponse(jsonResult)
    }
    
//    func searchItunesFor(searchTerm: String) {
//        
//        //Clean up the search terms by replacing spaces with +
//        var itunesSearchTerm = searchTerm.stringByReplacingOccurrencesOfString(" ",
//            withString: "+", options: NSStringCompareOptions.CaseInsensitiveSearch,
//            range: nil)
//        
//        var escapedSearchTerm = itunesSearchTerm.stringByAddingPercentEscapesUsingEncoding(NSUTF8StringEncoding)
//        
//        //var urlPath = "https://itunes.apple.com/search?term=(escapedSearchTerm)&media=music"
//        var urlPath = "http://staging.wheelzo.com/api/v2/users"
//        var url: NSURL! = NSURL(string: urlPath)
//        var request: NSURLRequest = NSURLRequest(URL: url)
//        var connection: NSURLConnection! = NSURLConnection(request: request,
//            delegate: self,startImmediately: false)
//        
//        println("Access Wheelzo API at URL \(url)")
//        
//        connection.start()
//    }
    
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
    
//    func getMe() {
//        
//        var urlPath = "http://staging.wheelzo.com/api/v2/users/me"
//        var url: NSURL! = NSURL(string: urlPath)
//        var request: NSURLRequest = NSURLRequest(URL: url)
//        var connection: NSURLConnection! = NSURLConnection(request: request,
//            delegate: self,startImmediately: false)
//        
//        println("Requesting: \(urlPath)")
//        
//        connection.start()
//    }
    
//    func getUserFromUserId(userId: int_fast64_t) {
//        
//        var urlPath = "http://staging.wheelzo.com/api/v2/users?id=\(userId)"
//        var url: NSURL! = NSURL(string: urlPath)
//        var request: NSURLRequest = NSURLRequest(URL: url)
//        var connection: NSURLConnection! = NSURLConnection(request: request,
//            delegate: self,startImmediately: false)
//        
//        println("Requesting: \(urlPath)")
//        
//        connection.start()
//    }
//    
//    func getUserFromFbId(fbId: int_fast64_t) {
//        
//        var urlPath = "http://staging.wheelzo.com/api/v2/users?id=\(fbId)"
//        var url: NSURL! = NSURL(string: urlPath)
//        var request: NSURLRequest = NSURLRequest(URL: url)
//        var connection: NSURLConnection! = NSURLConnection(request: request,
//            delegate: self,startImmediately: false)
//        
//        println("Requesting: \(urlPath)")
//        
//        connection.start()
//    }
    
    // ride stuff
    
    func getCurrentRides() {
        
        var urlPath = "http://staging.wheelzo.com/api/v2/rides"
        var url: NSURL! = NSURL(string: urlPath)
        var request: NSURLRequest = NSURLRequest(URL: url)
        var connection: NSURLConnection! = NSURLConnection(request: request,
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
    
    func postRide(ride: RideModel) {
        
        println("postRide unsupported")

    }
    
    func deleteRide(rideId: int_fast64_t) {
        
        println("deleteRide unsupported")
        
    }
    
    // comment stuff
    
    func postComment(commentText: String, rideId: Int, userId: Int) {
        
//        let requestDictionary = [
//            "comment" : commentText,
//            "rideID": rideId
//        ]
//        
//        var urlPath = "http://staging.wheelzo.com/api/v2/comments"
//        var url: NSURL! = NSURL(string: urlPath)
//        var request: NSMutableURLRequest = NSMutableURLRequest(URL: url)
//        
//        request.HTTPMethod = "POST";
//        
//        var connection: NSURLConnection! = NSURLConnection(request: request,
//            delegate: self,startImmediately: false)
//        
//        println("Requesting: \(urlPath)")
//        
//        connection.start()
        
        
        //posts a ride
        
        var session = FBSession()
        var token = session.accessTokenData.accessToken
        
        var urlPath = "http://staging.wheelzo.com/api/v2/comments"
        var url: NSURL! = NSURL(string: urlPath)
        
        let userId = 1;
        
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
            
            println("response = \(response)")
            
            let responseString = NSString(data: data, encoding: NSUTF8StringEncoding)
            println("responseString = \(responseString)")
        }
        task.resume()
        
        //end of post ride
        
        
        
        
        
        
        
        
        println("postComment unsupported")
        
        
        // other way
//        
//        let request: NSURLRequest = NSURLRequest(URL: imgUrl!)
//        let mainQueue = NSOperationQueue.mainQueue()
//        NSURLConnection.sendAsynchronousRequest(request, queue: mainQueue, completionHandler: { (response, data, error) -> Void in
//            if error == nil {
//                // Convert the downloaded data in to a UIImage object
//                //let image = UIImage(data: data)
//                // Store the image in to our cache
//                //self.imageCache[urlString] = image
//                // Update the cell
//                dispatch_async(dispatch_get_main_queue(), {
//                    
//                    if let cellToUpdate = tableView.cellForRowAtIndexPath(indexPath) as? RideTableCell {
//                        tableView.beginUpdates();
//                        cellToUpdate.profilePic.image = image;
//                        //cellToUpdate.profilePic.layer.cornerRadius = cellToUpdate.profilePic.image!.size.height/2;
//                        
//                        
//                        tableView.endUpdates();
//                    }
//                })
//            }
//                
//            else {
//                println("Error: \(error.localizedDescription)")
//            }
//        });
        // end of network request
        
        
        
        
    }
    
    func getComments(rideId: Int) {
        
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



