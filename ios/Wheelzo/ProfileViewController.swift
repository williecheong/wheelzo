//
//  ProfileViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-08-09.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit;


class ProfileViewController: UIViewController, UITableViewDataSource, UITableViewDelegate, WheelzoAPIProtocol {
    
    // class that takes care of user profile (the lookup feature)
    
    var api: WheelzoAPI = WheelzoAPI()
    
    var rideData : NSDictionary = NSDictionary();
    var imageData: UIImage! = UIImage();
    
    
    @IBOutlet var profilePic: UIImageView! = UIImageView();
    @IBOutlet var nameLabel: UILabel!
    
    @IBOutlet var mutualFriendsLabel: UILabel!
    @IBOutlet var mutualLikesLabel: UILabel!
    
    @IBOutlet var reviewsTableView : UITableView?
    var reviewsTableData: [NSDictionary] = [NSDictionary]()

    
    func loadMutualFriendsAndLikes() {
        
        let driverFbId = rideData["driver_facebook_id"] as! String;
        
        // generate appsecret_proof to get mutual friends
        let token : String = FBSDKAccessToken.currentAccessToken().tokenString;
        let key : String = "c52713e8a83d992b656a004d0ed908d4" ;
        var output = token.hmac(CryptoAlgorithm.SHA256, key: key)
        
        var parameters = NSMutableDictionary();
        parameters["fields"] = "context.fields(mutual_friends,mutual_likes)"
        parameters["appsecret_proof"] = output;
        
        // might have to set params to secret?
        let request = FBSDKGraphRequest(graphPath: "/\(driverFbId)" , parameters: parameters as [NSObject : AnyObject]);
        request.startWithCompletionHandler( { (connection, result, error) -> Void in
            
            if ((error) != nil) {
                // Process error
            } else {
//                println(result)
                
                var resultData = result as! NSDictionary;
                var contextData = resultData["context"] as! NSDictionary;
                
                if (contextData.valueForKey("mutual_friends") != nil) {
                    
                    // friends
                    var mutualFriendsData = contextData["mutual_friends"] as! NSDictionary;
                    var mutualFriendsSummaryData = mutualFriendsData["summary"] as! NSDictionary;
                    var mutualFriendsSummaryCount = mutualFriendsSummaryData["total_count"] as! Int;
                
                    self.mutualFriendsLabel.text = String(mutualFriendsSummaryCount);
                }
                
                if (contextData.valueForKey("mutual_likes") != nil ) {
                    // likes
                    var mutualLikesData = contextData["mutual_likes"] as! NSDictionary;
                    var mutualLikesSummaryData = mutualLikesData["summary"] as! NSDictionary;
                    var mutualLikesSummaryCount = mutualLikesSummaryData["total_count"] as! Int;
    
                    self.mutualLikesLabel.text = String(mutualLikesSummaryCount);

                }
                
            }
        })
        
        
    }
    
    func didRecieveRideResponse(results: NSArray) {
        // unused
    }
    
    func didRecieveUserResponse(results: NSArray) {
        // unused
    }
    
    func didRecieveReviewsResponse(results: NSArray) {
        // processes reviews
        
        if results.count > 0 {
            println("got: \(results.count)")
            self.reviewsTableData = results as! [NSDictionary]
            self.reviewsTableView!.reloadData()
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        self.nameLabel.text = rideData["driver_name"] as? String;
        
        self.profilePic.image = imageData;
        // rounded corners
        profilePic.layer.cornerRadius = profilePic.frame.size.width / 2;
        profilePic.clipsToBounds = true;
        
        
        api.delegate = self;
        
        let driverWheelzoId = rideData["driver_id"] as! String;
        api.getReviews(driverWheelzoId)
        
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        
        loadMutualFriendsAndLikes()
    }
    
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
    //table view functions
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        println(reviewsTableData.count)
        return reviewsTableData.count
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath:
        NSIndexPath) -> UITableViewCell {
            
            // load the correct nib
            tableView.registerNib(UINib(nibName: "ReviewTableCell", bundle: nil), forCellReuseIdentifier: "ReviewTableCell");
            
            // the tag of the cell
            let cellIdentifier: String = "ReviewTableCell"
            
            //the tablecell is optional to see if we can reuse cell
            var cell = tableView.dequeueReusableCellWithIdentifier(cellIdentifier, forIndexPath: indexPath) as! ReviewTableCell
            
            //Get our data row
            var rowData: NSDictionary = self.reviewsTableData[indexPath.row] as NSDictionary
            
            println("rowData \(rowData)")

            let dateString = rowData["last_updated"] as! String;
            
            // date formatting
            var dateFormatter = NSDateFormatter();
            
            // input format (don't change unless api changes)
            var formatString = "yyyy'-'MM'-'dd' 'HH':'mm':'ss";
            dateFormatter.dateFormat = formatString;
            let dateObject = dateFormatter.dateFromString(dateString);
            
            // date output
            //      Jun-26
            formatString = "EEEE', 'MMM' 'dd";
            dateFormatter.dateFormat = formatString;
            cell.dateLabel.text = dateFormatter.stringFromDate(dateObject!);
    
            let reviewString = rowData["review"] as! String;
            cell.reviewLabel.text = reviewString;
            
            return cell
    }
    
    
    
    func tableView(tableView: UITableView, heightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        // otherwise, resizable height
        return UITableViewAutomaticDimension;
    }
    
    func tableView(tableView: UITableView, estimatedHeightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        return 78;
    }
    
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        println("You selected cell #\(indexPath.row)!");
    }
    
    
    
    
}

