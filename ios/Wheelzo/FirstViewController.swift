////
////  FirstViewController.swift
////  Wheelzo
////
////  Created by Maksym Pikhteryev on 2015-01-08.
////  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
////
//
//import UIKit
//
//class FirstViewController: UIViewController, WheelzoAPIProtocol, UITableViewDataSource, UITableViewDelegate {
//
//    var api: WheelzoAPI = WheelzoAPI()
//    
//    @IBOutlet var appsTableView : UITableView?
//    var tableData: NSArray = NSArray()
//    var imageCache = NSMutableDictionary()
//
//    
//    override func viewDidLoad() {
//        super.viewDidLoad()
//        // Do any additional setup after loading the view, typically from a nib.
//        api.delegate = self;
//        api.searchItunesFor("Jimmy Buffett")
//        
//    }
//
//    override func didReceiveMemoryWarning() {
//        super.didReceiveMemoryWarning()
//        // Dispose of any resources that can be recreated.
//    }
//    
//    func didRecieveResponse(results: NSArray) {
//        // Store the results in our table data array
//        println("Received results")
//        println(results)
//        if results.count>0 {
//            self.tableData = results as NSArray
//            self.appsTableView?.reloadData()
//        }
//    }
//    
//    //table view functions
//    
//    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
//        return tableData.count
//    }
//    
//    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath:
//        NSIndexPath) -> UITableViewCell {
//            let kCellIdentifier: String = "MyCell"
//            
//            //the tablecell is optional to see if we can reuse cell
//            var cell : UITableViewCell?
//            cell = tableView.dequeueReusableCellWithIdentifier(kCellIdentifier) as UITableViewCell!
//            
//            //If we did not get a reuseable cell, then create a new one
//            if (cell? == nil) {
//                cell = UITableViewCell(style: UITableViewCellStyle.Subtitle, reuseIdentifier:
//                    kCellIdentifier)
//            }
//            
//            //Get our data row
//            var rowData: NSDictionary = self.tableData[indexPath.row] as NSDictionary
//            
//            println(rowData)
//            
//            //Set the track name
//            let cellText: String? = rowData["name"] as String?
//            cell?.textLabel?.text = cellText
//            println(cellText)
//            
//            // Get the track censored name
//            //var trackCensorName: NSString? = rowData["score"] as NSString?
//            //cell!.detailTextLabel?.description = trackCensorName
//            
//            //cell!.image = UIImage(named: "loading")
//            
//            /**
//            dispatch_async(dispatch_get_global_queue( DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), {
//                
//                // Grab the artworkUrl60 key to get an image URL
//                var urlString: NSString = rowData["artworkUrl60"] as NSString
//                
//                // Check the image cache for the key (using the image URL as key)
//                var image: UIImage? = self.imageCache.valueForKey(urlString) as? UIImage
//                
//                if(( image ) == nil) {
//                    // If the image does not exist in the cache then we need to download it
//                    var imgURL: NSURL = NSURL(string: urlString)!
//                    
//                    //Get the image from the URL
//                    var request: NSURLRequest = NSURLRequest(URL: imgURL)
//                    var urlConnection: NSURLConnection = NSURLConnection(request: request,
//                        delegate: self)!
//                    
//                    NSURLConnection.sendAsynchronousRequest(request, queue:
//                        NSOperationQueue.mainQueue(), completionHandler: {(response:
//                            NSURLResponse!,data: NSData!,error: NSError!) -> Void in
//                            
//                            if !(error? != nil) {
//                                image = UIImage(data: data)
//                                
//                                // Store the image in the cache
//                                self.imageCache.setValue(image, forKey: urlString)
//                                //cell!.setValue(value: image, forKey: "image") = image
//                                tableView.reloadData()
//                            }
//                            else {
//                                println("Error: \(error.localizedDescription)")
//                            }
//                    })
//                    
//                }
//                else {
//                    //cell!.image = image
//                }
//                
//                
//            })
//            **/
//            
//            cell?.imageView?.image = nil
//            return cell!
//            
//    }
//    
//    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
//        //get the selected tracks information
//        var rowData: NSDictionary = self.tableData[indexPath.row] as NSDictionary
//        
//        var name: String = rowData["trackCensoredName"] as String
//        var releaseDate: String = rowData["releaseDate"] as String
//        
//        //Show the alert view with the tracks information
//        var alert: UIAlertView = UIAlertView()
//        alert.title = name
//        alert.message = releaseDate
//        alert.addButtonWithTitle("Ok")
//        alert.show()
//    }
//
//
//}
//
