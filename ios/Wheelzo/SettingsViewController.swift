//
//  SettingsViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-06-29.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

//
//  PostRideViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-06-28.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit;

class SettingsViewController: UIViewController, FBSDKLoginButtonDelegate {
    
    // class that takes care of posting a new ride
    
    @IBOutlet var fbLoginView : FBSDKLoginButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
    }
    
    override func viewDidAppear(animated: Bool) {
        
        if (FBSDKAccessToken.currentAccessToken() != nil) {
            // User is already logged in, do work such as go to next view controller.
            
            // segue automatically if logged in
            performSegueWithIdentifier("fbAutoSegue", sender: self)
            
            
        } else {
            //let loginView : FBSDKLoginButton = FBSDKLoginButton()
            //self.view.addSubview(loginView)
            //loginView.center = self.view.center
            
            fbLoginView.readPermissions = ["public_profile", "email", "user_friends"]
            fbLoginView.delegate = self
            
        }
    }
    
    // Facebook Delegate Methods
    
    func loginButton(loginButton: FBSDKLoginButton!, didCompleteWithResult result: FBSDKLoginManagerLoginResult!, error: NSError!) {
        
        println("User Logged In")
        
        if ((error) != nil) {
            // Process error
        } else if result.isCancelled {
            // Handle cancellations
        } else {
            // If you ask for multiple permissions at once, you
            // should check if specific permissions missing
            if result.grantedPermissions.contains("email") {
                // Do work
            }
        }
    }
    
    func loginButtonDidLogOut(loginButton: FBSDKLoginButton!) {
        println("User Logged Out")
    }
    
    
    // old fb delegate methods
    
    //    func loginViewShowingLoggedInUser(loginView : FBLoginView!) {
    //        println("User Logged In")
    //        println("This is where you perform a segue.")
    //    }
    
    //    func loginViewFetchedUserInfo(loginView : FBLoginView!, user: FBGraphUser){
    //        println("User Name: \(user.name)")
    //        //println("other stuff: \(user)")
    //
    //
    //        var session = FBSession()
    //        var token = session.accessTokenData.accessToken
    //        //println("access token: \(token)")
    //        println("~~~~~")
    //
    //            }
    //
    //    func loginViewShowingLoggedOutUser(loginView : FBLoginView!) {
    //        println("User Logged Out")
    //    }
    //
    //    func loginView(loginView : FBLoginView!, handleError:NSError) {
    //        println("Error: \(handleError.localizedDescription)")
    //    }
    
    
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
}

