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

