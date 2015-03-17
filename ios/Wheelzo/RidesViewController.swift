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
        //api.searchItunesFor("Jimmy Buffett")
        
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
            self.appsTableView?.reloadData()
        }
    }
    
    //table view functions
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return tableData.count
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath:
        NSIndexPath) -> UITableViewCell {
            
            return UITableViewCell();
            
    }
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        
        
    }
    
    
}

