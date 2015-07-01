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
        super.viewDidAppear(animated)
        
        if (FBSDKAccessToken.currentAccessToken() != nil) {
            // User is already logged in
                        
        } else {
            
            fbLoginView.readPermissions = ["public_profile", "email", "user_friends"]
            
        }
    }
    
    // Facebook Delegate Methods
    
    func loginButton(loginButton: FBSDKLoginButton!, didCompleteWithResult result: FBSDKLoginManagerLoginResult!, error: NSError!) {
        
        println("User Logged In")
        
        if ((error) != nil) {
            // Process error
        } else if result.isCancelled {
            // Handle cancellations
            
            // do nothing, user will still have to login
            
        } else {
            // If you ask for multiple permissions at once, you
            // should check if specific permissions missing
//            if result.grantedPermissions.contains("email") {
//                // Do work
//            }
            
            // after user logs in using the button, send them to the main app
            performSegueWithIdentifier("settingsSegueToApp", sender: self)
            
        }
    }
    
    func loginButtonDidLogOut(loginButton: FBSDKLoginButton!) {
        println("User Logged Out")
        
        // stops user from navigating away after logging out
        //self.navigationController?.setNavigationBarHidden(true, animated: true);
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
}

