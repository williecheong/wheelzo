//
//  DetailRideViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-12.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit

class DetailRideViewController: UIViewController , WheelzoAPIProtocol, WheelzoCommentAPIProtocol {
    
    // class that will show a detailed view of a ride that is clicked on the wheelzo table
    

    // ui stuff
    
    @IBOutlet var profilePic: UIImageView! = UIImageView();
    
    @IBOutlet var nameLabel: UILabel!
    @IBOutlet var dateLabel: UILabel!
    @IBOutlet var priceLabel: UILabel!
    @IBOutlet var toLabel: UILabel!
    @IBOutlet var fromLabel: UILabel!
        
    @IBOutlet var postCommentText: UITextField!;
    @IBOutlet var postCommentButton: UIButton!;
    
    @IBOutlet var deleteRideButton: UIButton!;


    // wheelzo apis
    var api: WheelzoAPI = WheelzoAPI()
    var commentApi: WheelzoCommentAPI = WheelzoCommentAPI()

    
    // data passed from ride list view to detail view
    var image = UIImage();
    var rideData : NSDictionary = NSDictionary();
    
    // other data stuff (comments)
    var tableData = NSArray();

    func setInfo() {
        
        nameLabel.text = rideData["driver_name"] as! String?;
        
        println("the driver is \(nameLabel.text) ")
        
        fromLabel.text = rideData["origin"] as! String?;
        fromLabel.numberOfLines = 0
        fromLabel.sizeToFit()
        toLabel.text = rideData["destination"] as! String?;
        toLabel.numberOfLines = 0
        toLabel.sizeToFit()
        priceLabel.text = rideData["price"] as! String?;
        dateLabel.text = rideData["start"] as! String?;
        
    }
    
   
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        let driverFbId = rideData["driver_facebook_id"] as! String;
        
        // if this is not the same as the person logged in, hide the delete button
        if (driverFbId != FBSDKAccessToken.currentAccessToken().userID) {
            deleteRideButton.hidden = true;
        }
        
        // text stuff
        setInfo()
        
        profilePic.image = image;
        
        // rounded corners
        profilePic.layer.cornerRadius = profilePic.frame.size.width / 2;
        profilePic.clipsToBounds = true;
        
        // grab wheelzo driver id
        let driverId = rideData["driver_id"] as! String;
        
        
        println(rideData)
        
        api.delegate = self;
        commentApi.delegate = self;
        
        var rideId = rideData["id"]!.integerValue;
        println(rideData["id"])
        println(rideId)
        
        
        // gets the driver data
        api.getUserFromUserId(driverId.toInt()!);
        
        commentApi.getComments(rideId);
        
        // get fb user
        
        // get driver user
        
        // if they are the same, display delete button, otherwise hide it
        
        
        // todo: fix the weird behaviour of this:
        self.hidesBottomBarWhenPushed = true;
        
    }
    
    override func viewDidAppear(animated: Bool) {
        //println("reloading data")
        super.viewDidAppear(animated)
    }
    
    func didRecieveRideResponse(results: NSArray) {
        // not used
    }
    
    func didRecieveUserResponse(results: NSArray) {
        
        // todo: delete this whole thing
        
        if results.count>0 {
            
            // should be an array of one
            println("found user")
            
            var userData = results.firstObject as! NSDictionary

            
            var wheelzoId = userData["id"] as! String;
            var fbId = userData["facebook_id"] as! String;
            
            
            
            setProfilePicFromFbId(fbId);
            
        }
        
        
        // not used? can probably just pass data through the table cell
    }
    
    func didRecieveCommentResponse(results: NSArray) {
        
        println("detail recieved comment response")
        
        // comments loaded or posted new comment
        
        // Store the results in our table data array
        //println(results)
        if results.count>0 {
            self.tableData = results as NSArray
        }
        
        // todo: once comment data is recieved, load user data for every comment
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    

    
    @IBAction func postCommentButtonPressed(sender: AnyObject) {
        // button press events
        println("button was pressed");
        
        let commentText = postCommentText.text;

        let rideId = rideData["id"]!.integerValue;
        let userId = rideData["driver_id"]!.integerValue;
        
        
        
        
        // reloads comments to include new one
        // bug: this i slsightly too quick. need to have it as a callback
        //

    }
    
    @IBAction func deleteButtonPressed(sender: AnyObject) {

        println("delete button was pressed");
        
        let rideId = rideData["id"]!.integerValue;
        let userId = rideData["driver_id"]!.integerValue;
        

        var deleteAlert = UIAlertController(title: "Delete Ride?", message: "There is no going back.", preferredStyle: UIAlertControllerStyle.Alert)
        
        deleteAlert.addAction(UIAlertAction(title: "Delete", style: .Default, handler: { (action: UIAlertAction!) in
            println("Handle Ok logic here")
            
            // delete ride from server
            self.api.deleteRide(rideId);
            
            // perform segue back to ride list
            self.navigationController!.popToRootViewControllerAnimated(true);
            
        }))
        
        deleteAlert.addAction(UIAlertAction(title: "Cancel", style: .Default, handler: { (action: UIAlertAction!) in
            println("Handle Cancel Logic here")
            
            // do nothing
            
        }))
        
        presentViewController(deleteAlert, animated: true, completion: nil)
        
    }
    
    
    func setProfilePicFromFbId(fbId: String) {
        
        // fb picture lookup
        
        // todo need to convert driverId to fbId
        
        let fbUserId = fbId; //WheelzoAPI.getUserFromUserId(driverIdInt);
        
        let urlString = "https://graph.facebook.com/v2.3/\(fbUserId)/picture?type=large&redirect=true&width=128&height=128" as String
        let imgUrl = NSURL(string: urlString)
        
        let request: NSURLRequest = NSURLRequest(URL: imgUrl!)
        let mainQueue = NSOperationQueue.mainQueue()
        NSURLConnection.sendAsynchronousRequest(request, queue: mainQueue, completionHandler: { (response, data, error) -> Void in
            if error == nil {
                // Convert the downloaded data in to a UIImage object
                let image = UIImage(data: data)
                // Store the image in to our cache
                //self.imageCache[urlString] = image
                // Update the cell
                dispatch_async(dispatch_get_main_queue(), {
                    
                    self.profilePic.image = image;
                    
                })
            }
            else {
                println("Error: \(error.localizedDescription)")
            }
        });
        // end of network request
        
    }
    
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject!) {
        
        if (segue.identifier == "chatSegue") {
            // when user clicks chat button
            println("starting chat")
            
            var svc = segue.destinationViewController as! ChatViewController;
            
            // passes data about the ride to the detail view (will have to load picture later or something)
            svc.rideData = self.rideData;
            
        }
    }
    
    
    
    
}