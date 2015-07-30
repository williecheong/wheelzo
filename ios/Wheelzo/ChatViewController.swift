//
//  ChatViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-07-29.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit

import FBSDKCoreKit
import FBSDKLoginKit

class ChatViewController: JSQMessagesViewController, WheelzoCommentAPIProtocol {
    
    var commentApi: WheelzoCommentAPI = WheelzoCommentAPI()
    
    var commentData: [NSDictionary] = [NSDictionary]()

    
    var messages = [TextMessage]()
    
    var avatars = Dictionary<String, JSQMessagesAvatarImage>()
    
    var outgoingBubbleImageView = JSQMessagesBubbleImageFactory().outgoingMessagesBubbleImageWithColor(UIColor.jsq_messageBubbleLightGrayColor())
    var incomingBubbleImageView =
        JSQMessagesBubbleImageFactory().incomingMessagesBubbleImageWithColor(UIColor.jsq_messageBubbleGreenColor())

    
    // ride data, destination, etc,etc
    
    let myFbId = FBSDKAccessToken.currentAccessToken().userID
    var rideId : String = "";
    
    var rideData : NSDictionary = NSDictionary();
    
    func didRecieveCommentResponse(results: NSArray) {
        
        println("chat recieved response")
        
        // comments loaded or posted new comment
        
        // Store the results in our table data array
        //println(results)
        if results.count>0 {
            
            // clears old messages out
            messages.removeAll(keepCapacity: true)
            
            self.commentData = results as! [NSDictionary]
            
            for i in 0...results.count-1 {
            
                var rowData: NSDictionary = self.commentData[i]
                
                var text = rowData["comment"] as! String!;
                //let senderId = rowData["user_id"] as! String!;
                let senderId = rowData["user_facebook_id"] as! String!;
                let senderDisplayName = rowData["user_name"] as! String;
                
                // todo, probably need to have a date posted field
                // date formatting
                let dateString = rowData["last_updated"] as! String;
                var dateFormatter = NSDateFormatter();
                
                // input format (don't change unless api changes)
                var formatString = "yyyy'-'MM'-'dd' 'HH':'mm':'ss";
                dateFormatter.dateFormat = formatString;
                let date = dateFormatter.dateFromString(dateString);
                
                let fbUserId = rowData["user_facebook_id"] as! String!;
                

                println(fbUserId)
                
                // workaround for auto-gen comments
                if text.rangeOfString("<em>") != nil {
                    text = "This ride has been imported, \(senderDisplayName) may be unaware of comments posted here."
                }
                
                
                let message = TextMessage(senderId: senderId, senderDisplayName: senderDisplayName, date: date, text: text)
                message.fbUserId = fbUserId;
                
                messages.append(message)
            }
            
        }
        
        self.collectionView.reloadData()
    }
    
    func setupChat() {
        commentApi.delegate = self;
        
        // have to load own profile
        self.senderId = myFbId
        self.senderDisplayName = FBSDKProfile.currentProfile().firstName
        
        rideId = rideData["id"] as! String;
        
        println("setting id to \(self.senderId)")
        
        // request comments
        commentApi.getComments(rideId.toInt()!)
    }

    override func viewDidLoad() {
        super.viewDidLoad()
        setupChat()
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        collectionView.collectionViewLayout.springinessEnabled = true
    }
    
    override func viewDidDisappear(animated: Bool) {
        super.viewDidAppear(animated)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func setupAvatarImage(name: String, imageUrl: String?, incoming: Bool) {
        if let stringUrl = imageUrl {
            if let url = NSURL(string: stringUrl) {
                if let data = NSData(contentsOfURL: url) {
                    let image = UIImage(data: data)
                    let diameter = incoming ? UInt(collectionView.collectionViewLayout.incomingAvatarViewSize.width) : UInt(collectionView.collectionViewLayout.outgoingAvatarViewSize.width)
                    let avatarImage = JSQMessagesAvatarImageFactory.avatarImageWithImage(image, diameter: diameter)
                    avatars[name] = avatarImage
                    return
                }
            }
        }
        
        // At some point, we failed at getting the image (probably broken URL), so default to avatarColor
        setupAvatarColor(name, incoming: incoming)
    }
    
    func setupAvatarColor(name: String, incoming: Bool) {
        let diameter = incoming ? UInt(collectionView.collectionViewLayout.incomingAvatarViewSize.width) : UInt(collectionView.collectionViewLayout.outgoingAvatarViewSize.width)
        
        let rgbValue = name.hash
        let r = CGFloat(Float((rgbValue & 0xFF0000) >> 16)/255.0)
        let g = CGFloat(Float((rgbValue & 0xFF00) >> 8)/255.0)
        let b = CGFloat(Float(rgbValue & 0xFF)/255.0)
        let color = UIColor(red: r, green: g, blue: b, alpha: 0.5)
        
        let nameLength = count(name)
        let initials : String? = name.substringToIndex(advance(name.startIndex, min(3, nameLength)))
        let userImage = JSQMessagesAvatarImageFactory.avatarImageWithUserInitials(initials, backgroundColor: color, textColor: UIColor.blackColor(), font: UIFont.systemFontOfSize(CGFloat(13)), diameter: diameter)
        
        avatars[name] = userImage
    }
    
    // Actions
    
    func sendMessage(text: String!) {
        
        let callback: ()->Void = {
            // seems like I need to delay this even further to account for server processing time
            sleep(2)
            self.commentApi.getComments(self.rideId.toInt()!)
        };
        
        commentApi.postComment(text, rideId: self.rideId.toInt()!, callback: callback);
        
        finishReceivingMessage()
        finishSendingMessage()
    }
    
    override func didPressSendButton(button: UIButton!, withMessageText text: String!, senderId: String!, senderDisplayName: String!, date: NSDate!) {
        
        JSQSystemSoundPlayer.jsq_playMessageSentSound()
        
        sendMessage(text)
        
        finishSendingMessage()
    }
    
    override func didPressAccessoryButton(sender: UIButton!) {
        println("Camera pressed!")
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, messageDataForItemAtIndexPath indexPath: NSIndexPath!) -> JSQMessageData! {
        return messages[indexPath.item]
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, messageBubbleImageDataForItemAtIndexPath indexPath: NSIndexPath!) -> JSQMessageBubbleImageDataSource! {
    
        // check if yours or someone elses messages, and display accordingly
        let message = messages[indexPath.item]
        
        if message.fbUserId == myFbId {
            return outgoingBubbleImageView;
        } else {
            return incomingBubbleImageView;
        }
        
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, attributedTextForCellTopLabelAtIndexPath indexPath: NSIndexPath!) -> NSAttributedString! {
    
        let message = messages[indexPath.item];
        
        // Sent by me, skip
        if message.fbUserId == myFbId {
            return nil;
        }
        
        // Same as previous sender, skip
        if indexPath.item > 0 {
            let previousMessage = messages[indexPath.item - 1];
            if previousMessage.fbUserId == message.fbUserId {
                return nil;
            }
        }
        
        return NSAttributedString(string: message.senderDisplayName)
    
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, attributedTextForMessageBubbleTopLabelAtIndexPath indexPath: NSIndexPath!) -> NSAttributedString! {
        
        let message = messages[indexPath.item]
        
        return NSAttributedString(string: message.senderDisplayName)
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, avatarImageDataForItemAtIndexPath indexPath: NSIndexPath!) -> JSQMessageAvatarImageDataSource! {
        
        let message = messages[indexPath.item]
        
        if let avatar = avatars[message.senderId] {
            return JSQMessagesAvatarImageFactory.avatarImageWithImage(avatar.avatarImage, diameter: 32)
        } else {
            
            let imageUrl = "https://graph.facebook.com/v2.3/\(message.fbUserId)/picture" as String
            
            setupAvatarImage(message.senderId, imageUrl: imageUrl, incoming: true)
            let avatar = avatars[messages[indexPath.item].senderId]
            return JSQMessagesAvatarImageFactory.avatarImageWithImage(avatar?.avatarImage, diameter: 32)
        }
        
    }
    
    override func collectionView(collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return messages.count
    }
    
    override func collectionView(collectionView: UICollectionView, cellForItemAtIndexPath indexPath: NSIndexPath) -> UICollectionViewCell {
        let cell = super.collectionView(collectionView, cellForItemAtIndexPath: indexPath) as! JSQMessagesCollectionViewCell
        
        let message = messages[indexPath.item]
        if message.fbUserId == myFbId {
            cell.textView.textColor = UIColor.blackColor()
        } else {
            cell.textView.textColor = UIColor.whiteColor()
        }
        
        let attributes : [NSObject:AnyObject] = [NSForegroundColorAttributeName:cell.textView.textColor, NSUnderlineStyleAttributeName: 1]
        
        cell.textView.linkTextAttributes = attributes
        
        //        cell.textView.linkTextAttributes = [NSForegroundColorAttributeName: cell.textView.textColor,
        //            NSUnderlineStyleAttributeName: NSUnderlineStyle.StyleSingle]
        return cell
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, layout collectionViewLayout: JSQMessagesCollectionViewFlowLayout!, heightForMessageBubbleTopLabelAtIndexPath indexPath: NSIndexPath!) -> CGFloat {
        let message = messages[indexPath.item]
        
        // Sent by me, skip
        if message.fbUserId == myFbId {
            return CGFloat(0.0);
        }
        
        // Same as previous sender, skip
        if indexPath.item > 0 {
            let previousMessage = messages[indexPath.item - 1];
            if previousMessage.fbUserId == message.fbUserId {
                return CGFloat(0.0);
            }
        }
        
        return kJSQMessagesCollectionViewCellLabelHeightDefault
    }
    
    
    
    
    

}
