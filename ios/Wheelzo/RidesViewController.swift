//
//  RidesViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-02-12.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation


//
//  FirstViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-01-08.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import UIKit

import FBSDKCoreKit
import FBSDKLoginKit

class RidesViewController: UIViewController, UITableViewDataSource, UITableViewDelegate, WheelzoAPIProtocol {
    
    var api: WheelzoAPI = WheelzoAPI()
    
    @IBOutlet var appsTableView : UITableView?
    var tableData: NSArray = NSArray()
    var imageCache = NSMutableDictionary()
    
    // loading checkpoints
    var ridesLoaded: Bool = false;
    
    var refreshControl: UIRefreshControl!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        api.delegate = self;
        
        if(ridesLoaded == false) {
            api.getCurrentRides();
            ridesLoaded = true;
        } else {
            
        }
        
        
        
        // it appears as though the parent frame is bigger than the emulator display window
        
        
        refreshControl = UIRefreshControl(frame: CGRectMake(0, 0, 20, 20));
        refreshControl.tintColor = UIColor.purpleColor();
        
        
        //refreshControl.center = CGPoint(x: (appsTableView!.bounds.width)/2, y: (appsTableView!.bounds.height)/2)
        //refreshControl.center = CGPoint(x: 0, y: (appsTableView!.bounds.height)/2)
        //refreshControl.autoresizingMask = .FlexibleLeftMargin | .FlexibleRightMargin | .FlexibleTopMargin | .FlexibleBottomMargin
        
        
        
        appsTableView!.addSubview(refreshControl);
        
        
//        self.appsTableView.registerClass(UITableViewCell.self, forCellReuseIdentifier: "cell");
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        
        if (FBSDKAccessToken.currentAccessToken() != nil) {
            // User is already logged in
            // carry on
        } else {
            performSegueWithIdentifier("segueToLogin", sender: self);
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func didRecieveResponse(results: NSArray) {
        // Store the results in our table data array
        //println("Received results")
        //println(results)
        if results.count>0 {
            self.tableData = results as NSArray
            self.appsTableView!.reloadData()
        }
    }
    
    //table view functions
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return tableData.count
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath:
        NSIndexPath) -> UITableViewCell {
            
            // LOAD UP THE NIB FILE FOR THE CELL
//            var cellNib = UINib(nibName:"RideTableCell", bundle: nil);
            
            tableView.registerNib(UINib(nibName: "RideTableCell", bundle: nil), forCellReuseIdentifier: "RideTableCell");
            
            // how the app generates the cell in the table
            
            // the tag of the cell
            let cellIdentifier: String = "RideTableCell"
            
            //the tablecell is optional to see if we can reuse cell
            var cell = tableView.dequeueReusableCellWithIdentifier(cellIdentifier, forIndexPath: indexPath) as! RideTableCell
            
            
            //Get our data row
            var rowData: NSDictionary = self.tableData[indexPath.row] as! NSDictionary
            
            // all data
            cell.rideData = rowData;
            
            
            cell.toLabel.text = rowData["destination"] as! String?;
            cell.fromLabel.text = rowData["origin"] as! String?;
            
            var priceString = "$";
            priceString += rowData["price"] as! String;
            cell.priceLabel.text = priceString;
            
            let dateString = rowData["start"] as! String;
            
            
            
            // date formatting
            var dateFormatter = NSDateFormatter();
            
            // input format
            var formatString = "yyyy'-'MM'-'dd' 'HH':'mm':'ss";
            dateFormatter.dateFormat = formatString;
            let dateObject = dateFormatter.dateFromString(dateString);
            
            // date output
            //      Jun-26 @ 8:00pm
            formatString = "MMM'-'dd' @ 'h':'mm a";
            dateFormatter.dateFormat = formatString;
            cell.dateLabel.text = dateFormatter.stringFromDate(dateObject!);
  
            // day output
            formatString = "EEEE";
            dateFormatter.dateFormat = formatString;
            cell.dayLabel.text = dateFormatter.stringFromDate(dateObject!);
            
            
//            cell.dateLabel.text = rowData["start"] as String?;
            
            
            // Start by setting the cell's image to a static file
            // Without this, we will end up without an image view!
            
            cell.profilePic.image = UIImage(named: "empty_user");
            
            cell.profilePic.layer.cornerRadius = cell.profilePic.frame.size.width / 2;
            
            cell.profilePic.clipsToBounds = true;

            
            // api for fb picture lookup
            
            // todo need to convert driverId to fbId

            var fbUserID = 4; //WheelzoAPI.getUserFromUserId(driverIdInt);
            
            var urlString = "https://graph.facebook.com/v2.3/\(fbUserID)/picture" as String
            var imgUrl = NSURL(string: urlString)

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
                        if let cellToUpdate = tableView.cellForRowAtIndexPath(indexPath) as? RideTableCell {
                            tableView.beginUpdates();
                            cellToUpdate.profilePic.image = image;
                            //cellToUpdate.profilePic.layer.cornerRadius = cellToUpdate.profilePic.image!.size.height/2;

                            
                            tableView.endUpdates();
                        }
                    })
                }
                else {
                    println("Error: \(error.localizedDescription)")
                }
            });
            // end of network request
            
//            if (tableData.count == tableView.indexPathsForVisibleRows()?.count && indexPath.row == tableView.indexPathsForVisibleRows()?.last?.row) {
                // runs on last cell update
                
                // if our data is the same length as the number of cellsin the table view (but won't it always be?)
                
                // do nothing
//                println("last row!")
//                ridesLoad ed = true;
//                tableView.reloadData();
                
//            }
            
            return cell
    }
    
 
    
    func tableView(tableView: UITableView, heightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        return 78;
    }
    
    func tableView(tableView: UITableView, estimatedHeightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        return 78;
    }
    
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        
        println("You selected cell #\(indexPath.row)!");
        
        var selectedCell = tableView.cellForRowAtIndexPath(indexPath)! as! RideTableCell
        
        self.performSegueWithIdentifier("rideSelectSegue", sender: selectedCell);
        
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject!) {
        
        if (segue.identifier == "rideSelectSegue") {
            // when user selects one of the rides in the table

            
//            self.storyboard?.instantiateViewControllerWithIdentifier("DetailRideViewController")
            
        
            var svc = segue.destinationViewController as! DetailRideViewController;
            
            var senderCell = sender as! RideTableCell;
            
            //sprintln(svc.profilePic)
            
            
            // passes data about the ride to the detail view
            svc.rideData = senderCell.rideData;
            
            svc.image = senderCell.profilePic.image!;
              
            
            
            // or do we need .image?
            svc.profilePic = senderCell.profilePic;
            
            svc.toLabel = senderCell.toLabel;
            
            //println(svc.rideData)
            
//            var indexPath = tableView.indexPathForSelectedRow;
            
            
        
        }
    }
    
    
    
    
    
//    func setImageOfCell(tableView: UITableView, cell: UITableViewCell) {
//        
//    }
    
    
}

