//
//  DetailRideViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-12.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit

class DetailRideViewController: UIViewController , UITableViewDelegate, UITableViewDataSource ,WheelzoAPIProtocol {
    
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


    // wheelzo api
    var api: WheelzoAPI = WheelzoAPI()

    
    // data passed from ride list view to detail view
    var image = UIImage();
    var rideData : NSDictionary = NSDictionary();
    
    // other data stuff
    var tableData = NSArray();

   
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
                
        fromLabel.text = rideData["origin"] as! String?;
        toLabel.text = rideData["destination"] as! String?;
        
        priceLabel.text = rideData["price"] as! String?;
        dateLabel.text = rideData["start"] as! String?;
        
        profilePic.image = image;
        
        
        println(rideData)
        
        api.delegate = self;
        var rideId = rideData["id"]!.integerValue;
        println(rideData["id"])
        println(rideId)
        api.getComments(rideId);
        
        
    }
    
    override func viewDidAppear(animated: Bool) {
        //println("reloading data")
        super.viewDidAppear(animated)
        commentsTableView.reloadData();
    }
    
    func didRecieveResponse(results: NSArray) {
        
        println("detail recieved response")
        
        // comments loaded or posted new comment
        
        // Store the results in our table data array
        //println(results)
        if results.count>0 {
            self.tableData = results as NSArray
            self.commentsTableView!.reloadData()
        }
        
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
            
            println("loaded cell")
            
            
        return cell;
    }
    
    @IBAction func postCommentButtonPressed(sender: AnyObject) {
        // button press events
        println("button was pressed");
        
        let commentText = postCommentText.text;

        let rideId = rideData["id"]!.integerValue;
        let userId = rideData["driver_id"]!.integerValue;
        
        api.postComment(commentText, rideId: rideId, userId: userId);
        
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
    
}