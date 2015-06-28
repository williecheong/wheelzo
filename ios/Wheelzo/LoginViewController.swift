//
//  LoginViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-01-08.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit

class LoginViewController: UIViewController, FBLoginViewDelegate {
    
    //@IBOutlet var fbLoginView : FBLoginView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        // add fb login button
        
        var loginView = FBLoginView()
        self.view.addSubview(loginView)
        
        loginView.delegate = self
        loginView.readPermissions = ["public_profile", "email", "user_friends"]
        
        
    }
    
    // fb delegate methods
    
    func loginViewShowingLoggedInUser(loginView : FBLoginView!) {
        println("User Logged In")
        println("This is where you perform a segue.")
    }
    
    func loginViewFetchedUserInfo(loginView : FBLoginView!, user: FBGraphUser){
        println("User Name: \(user.name)")
        //println("other stuff: \(user)")
        
        
        var session = FBSession()
        var token = session.accessTokenData.accessToken
        //println("access token: \(token)")
        println("~~~~~")
        
        //posts a ride
        
        var urlPath = "http://staging.wheelzo.com/api/v2/rides"
        var url: NSURL! = NSURL(string: urlPath)
        
        let userId = 1;
        
        let request = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "POST"
        let postString = "{\"origin\": \"Waterloo\",\"destination\": \"Toronto\",\"departureDate\":\"2016-09-23\",\"departureTime\": \"00:00:00\",\"capacity\": \"2\",\"price\": \"10\",\"dropOffs\": [\"Milton\",\"Mississauga\"]}"
        
        println(postString)
        
        request.HTTPBody = postString.dataUsingEncoding(NSUTF8StringEncoding, allowLossyConversion: false)
        
        let tokenHeader = "Fb-Wheelzo-Token"

        let originS : String = "Waterloo";
        
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")

        request.addValue(token, forHTTPHeaderField: "Fb-Wheelzo-Token")
        
        //request.addValue(<#value: String?#>, forHTTPHeaderField: <#String#>)

        
        //println(postString)
        
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
    
    func loginViewShowingLoggedOutUser(loginView : FBLoginView!) {
        println("User Logged Out")
    }
    
    func loginView(loginView : FBLoginView!, handleError:NSError) {
        println("Error: \(handleError.localizedDescription)")
    }

    
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
}

