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

class RidesViewController: UIViewController, UITableViewDataSource, UITableViewDelegate, WheelzoAPIProtocol {
    
    var api: WheelzoAPI = WheelzoAPI()
    
    @IBOutlet var appsTableView : UITableView?
    var tableData: NSArray = NSArray()
    var imageCache = NSMutableDictionary()
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        api.delegate = self;
        api.getCurrentRides();
        
//        self.appsTableView.registerClass(UITableViewCell.self, forCellReuseIdentifier: "cell");
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func didRecieveResponse(results: NSArray) {
        // Store the results in our table data array
        println("Received results")
        println(results)
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
            
            let kCellIdentifier: String = "MyCell"
            
            //the tablecell is optional to see if we can reuse cell
            var cell : UITableViewCell?
            cell = tableView.dequeueReusableCellWithIdentifier(kCellIdentifier) as UITableViewCell!
            
            //If we did not get a reuseable cell, then create a new one
            if (cell? == nil) {
                cell = UITableViewCell(style: UITableViewCellStyle.Subtitle, reuseIdentifier:
                    kCellIdentifier)
            }
            
//            var cell:UITableViewCell = self.appsTableView!.dequeueReusableCellWithIdentifier("MyCell") as UITableViewCell;
            
//            cell?.textLabel?.text = self.items[indexPath.row]
            
            //Get our data row
            var rowData: NSDictionary = self.tableData[indexPath.row] as NSDictionary
            
            println(rowData)
            
            //Set the track name
            let cellText: String? = rowData["destination"] as String?
            cell?.textLabel?.text = cellText
            
            return cell!
            
//            return UITableViewCell();
            
    }
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        
        println("You selected cell #\(indexPath.row)!")
       
        
    }
    
    
}

