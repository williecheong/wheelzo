//
//  DetailRideViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-12.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit

class DetailRideViewController: UIViewController , UITableViewDelegate, UITableViewDataSource,
WheelzoAPIProtocol, WheelzoCommentAPIProtocol {
    
    // class that will show a detailed view of a ride that is clicked on the wheelzo table
    

    // ui stuff
    
    @IBOutlet var profilePic: UIImageView! = UIImageView();
    
    @IBOutlet var nameLabel: UILabel!
    @IBOutlet var dateLabel: UILabel!
    @IBOutlet var priceLabel: UILabel!
    @IBOutlet var toLabel: UILabel!
    @IBOutlet var fromLabel: UILabel!
    
    @IBOutlet var commentsTableView: UITableView!
    
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

   
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
                
        fromLabel.text = rideData["origin"] as! String?;
        toLabel.text = rideData["destination"] as! String?;
        
        priceLabel.text = rideData["price"] as! String?;
        dateLabel.text = rideData["start"] as! String?;
        
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
        
        
    }
    
    override func viewDidAppear(animated: Bool) {
        //println("reloading data")
        super.viewDidAppear(animated)
        commentsTableView.reloadData();
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
            
            // if this is not the same as the person logged in, hide the delete button
            if (fbId != FBSDKAccessToken.currentAccessToken().userID) {
                
                println("the driver is not logged in! will hide delete button")
                deleteRideButton.hidden = true;
                
            }
            
            setProfilePicFromFbId(fbId);
            
            self.commentsTableView!.reloadData()
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
        
        commentsTableView.reloadData()
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
    // comment table view functions
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return tableData.count
    }
    
    func tableView(tableView: UITableView, heightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        // resizable height
        return UITableViewAutomaticDimension;
    }
    
    func tableView(tableView: UITableView, estimatedHeightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        return 120;
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath:
        NSIndexPath) -> UITableViewCell {
            
            tableView.registerNib(UINib(nibName: "CommentsTableCell", bundle: nil), forCellReuseIdentifier: "CommentsTableCell");
            let cellIdentifier: String = "CommentsTableCell"
            var cell = tableView.dequeueReusableCellWithIdentifier(cellIdentifier, forIndexPath: indexPath) as! CommentsTableCell
            
            var rowData: NSDictionary = self.tableData[indexPath.row] as! NSDictionary
            
            cell.commentLabel.text = rowData["comment"] as! String?;
            cell.dateLabel.text = rowData["last_updated"] as! String?;
            cell.profilePic.image = UIImage(named: "empty_user");
            
            // todo: look up the user info for the comments (should do after comments have loaded)
            
            // placeholder
            cell.nameLabel.text = "";
            
            // update pics and name
            // todo: remove this and just grab from jsono
            
            
            let userId = rowData["user_id"] as! String;
            {
                self.api.syncGetUserDataFromUserId(userId);
            } ~> {
                // $0 is the user data nsdictionary
                let name = $0["name"] as! String;
                println("got name \(name)");
                cell.nameLabel.text = name;
                let fbId = $0["facebook_id"] as! String;
                self.setProfilePicForCommentUsingFbId(fbId, cell: cell)
            };
            
            println("loaded cell")
            
        return cell;
    }
    
    @IBAction func postCommentButtonPressed(sender: AnyObject) {
        // button press events
        println("button was pressed");
        
        let commentText = postCommentText.text;

        let rideId = rideData["id"]!.integerValue;
        let userId = rideData["driver_id"]!.integerValue;
        
        let callback: ()->Void = {
            self.commentApi.getComments(rideId)
            };
        
        commentApi.postComment(commentText, rideId: rideId, userId: userId, callback: callback);
        
        
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
        
        let urlString = "https://graph.facebook.com/v2.3/\(fbUserId)/picture" as String
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
    
    func setProfilePicForCommentUsingFbId(fbId: String, cell: CommentsTableCell) {
        
        // fb picture lookup
        
        // todo need to convert driverId to fbId
        
        let fbUserID = fbId; //WheelzoAPI.getUserFromUserId(driverIdInt);
        
        let urlString = "https://graph.facebook.com/v2.3/\(fbUserID)/picture" as String
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
                    
                    // rounded corners
                    cell.profilePic.layer.cornerRadius = cell.profilePic.frame.size.width / 2;
                    cell.profilePic.clipsToBounds = true;
                    
                    cell.profilePic.image = image;
                    
                })
            }
            else {
                println("Error: \(error.localizedDescription)")
            }
        });
        // end of network request
        
        
    }
    
    
    
}