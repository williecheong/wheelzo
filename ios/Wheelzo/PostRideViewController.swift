//
//  PostRideViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-06-28.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit;

class PostRideViewController: UIViewController, WheelzoAPIProtocol {
    
    // class that takes care of posting a new ride
    
    
    // ui stuff
    
    @IBOutlet var originText: UITextField!
    @IBOutlet var destinationText: UITextField!
    @IBOutlet var priceText: UITextField!
    @IBOutlet var capacityText: UITextField!

    
    @IBOutlet var dateTimePicker: UIDatePicker!
    
    @IBOutlet var postRideButton: UIButton!;
    
    @IBOutlet var tapGestureRecognizer: UITapGestureRecognizer!;
    
    
    // wheelzo api
    var api: WheelzoAPI = WheelzoAPI()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        
        priceText.keyboardType = UIKeyboardType.DecimalPad;
        capacityText.keyboardType = UIKeyboardType.DecimalPad;
        
        
        api.delegate = self;
    }
    
    override func viewDidAppear(animated: Bool) {
        //println("reloading data")
        super.viewDidAppear(animated)
    }
    
    func didRecieveResponse(results: NSArray) {
        
        println("ride post recieved response")
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    @IBAction func myUIImageViewTapped(recognizer: UITapGestureRecognizer) {
        if(recognizer.state == UIGestureRecognizerState.Ended){
            
            self.view.endEditing(true);
            
        }
    }
    
    @IBAction func buttonPressed(sender: AnyObject) {
        // button press events
        println("post ride button was pressed");
        
        let originString = originText.text;
        let destinationString = destinationText.text;
        let priceString = priceText.text;
        let capacityString = capacityText.text;
        
        let priceInt = priceString.toInt()!;
        let capacityInt = capacityString.toInt()!;
        
        // todo conver date to date+time components
        
        // date formatting
        var dateFormatter = NSDateFormatter();
        
        // date output
        var formatString = "yyyy'-'MM'-'dd";
        dateFormatter.dateFormat = formatString;
        let dateString = dateFormatter.stringFromDate(dateTimePicker.date);
        
        // time output
        //      Jun-26 @ 8:00pm
        formatString = "HH':'mm':'ss";
        dateFormatter.dateFormat = formatString;
        let timeString = dateFormatter.stringFromDate(dateTimePicker.date);

        
        let driverId = 1;
        
        api.postRide(driverId, origin: originString, destination: destinationString, capacity: capacityInt, price: priceInt, departureDate: dateString, departureTime: timeString);
        
        //let commentText = postCommentText.text;
        // todo pull rideId
        //let rideId = rideData["id"]!.integerValue;
        //let userId = rideData["driver_id"]!.integerValue;
        
        //api.postComment(commentText, rideId: rideId, userId: userId);
        
        
        
    }
    
}