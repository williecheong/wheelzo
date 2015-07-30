//
//  ChatViewController.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-07-29.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import Foundation

import UIKit


class ChatViewController: JSQMessagesViewController {
    
    var messages = [TextMessage]()
    
    var avatars = Dictionary<String, JSQMessagesAvatarImage>()
    
    var outgoingBubbleImageView = JSQMessagesBubbleImageFactory().outgoingMessagesBubbleImageWithColor(UIColor.jsq_messageBubbleLightGrayColor())
    var incomingBubbleImageView =
        JSQMessagesBubbleImageFactory().incomingMessagesBubbleImageWithColor(UIColor.jsq_messageBubbleGreenColor())
    
    var senderImageUrl: String!
    var batchMessages = true
//    var ref: Firebase!

    
//    func setupFirebase() {
//        // *** STEP 2: SETUP FIREBASE
//        messagesRef = Firebase(url: "https://swift-chat.firebaseio.com/messages")
//        
//        // *** STEP 4: RECEIVE MESSAGES FROM FIREBASE (limited to latest 25 messages)
//        messagesRef.queryLimitedToNumberOfChildren(25).observeEventType(FEventType.ChildAdded, withBlock: { (snapshot) in
//            let text = snapshot.value["text"] as? String
//            let sender = snapshot.value["sender"] as? String
//            let imageUrl = snapshot.value["imageUrl"] as? String
//            
//            let message = Message(text: text, sender: sender, imageUrl: imageUrl)
//            self.messages.append(message)
//            self.finishReceivingMessage()
//        })
//    }
    
    func createFakeMessages() {
        
        let fakeMessage1 = TextMessage(senderId: senderId, displayName: "dude", text: "guys")
        let fakeMessage2 = TextMessage(senderId: senderId, displayName: "dude2", text: "guys")
        let fakeMessage3 = TextMessage(senderId: "10", displayName: "some other dude", text: "are you excited?")
        let fakeMessage4 = TextMessage(senderId: senderId, displayName: "dude3", text: "you should be")
        
        
        messages.append(fakeMessage1)
        messages.append(fakeMessage2)
        messages.append(fakeMessage3)
        messages.append(fakeMessage4)
        
    }
    
    func setupChat(){
        
        // have to load own profile
        senderId = "0"
        senderDisplayName = "Max P"
        
    }

    override func viewDidLoad() {
        super.viewDidLoad()
        
        setupChat()
        
        createFakeMessages()
        
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        //collectionView.collectionViewLayout.springinessEnabled = true
    }
    
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    func sendMessage(text: String!, senderId: String!, senderDisplayName: String!) {
        // *** STEP 3: ADD A MESSAGE TO FIREBASE
//        messagesRef.childByAutoId().setValue([
//            "text":text,
//            "sender":sender,
//            "imageUrl":senderImageUrl
//            ])
        
        // temp
        
//        let message = Message(text: text, sender: sender, imageUrl: senderImageUrl)
        let message = TextMessage(senderId: senderId, displayName: senderDisplayName, text: text)
        messages.append(message)
        
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
        let initials : String? = name.substringToIndex(advance(senderId.startIndex, min(3, nameLength)))
        let userImage = JSQMessagesAvatarImageFactory.avatarImageWithUserInitials(initials, backgroundColor: color, textColor: UIColor.blackColor(), font: UIFont.systemFontOfSize(CGFloat(13)), diameter: diameter)
        
        avatars[name] = userImage
    }
    
    // Actions
    
    override func didPressSendButton(button: UIButton!, withMessageText text: String!, senderId: String!, senderDisplayName: String!, date: NSDate!) {
        
        JSQSystemSoundPlayer.jsq_playMessageSentSound()
        
        sendMessage(text, senderId: senderId, senderDisplayName: senderDisplayName)
        
        finishSendingMessage()
    }
    
    override func didPressAccessoryButton(sender: UIButton!) {
        println("Camera pressed!")
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, messageDataForItemAtIndexPath indexPath: NSIndexPath!) -> JSQMessageData! {
        return messages[indexPath.item]
    }
    
    override func collectionView(collectionView: JSQMessagesCollectionView!, messageBubbleImageDataForItemAtIndexPath indexPath: NSIndexPath!) -> JSQMessageBubbleImageDataSource! {
    
        // check if yours or someone elses messages, and colour accordingly
        let message = messages[indexPath.item]
        
        if message.senderId == senderId {
            return outgoingBubbleImageView;
        } else {
            return incomingBubbleImageView;
        }
        
    }
    
    // View  usernames bellow bubbles
    override func collectionView(collectionView: JSQMessagesCollectionView!, attributedTextForCellTopLabelAtIndexPath indexPath: NSIndexPath!) -> NSAttributedString! {
    
        let message = messages[indexPath.item];
        
        println("bubble text")
        
        // Sent by me, skip
        if message.senderId == senderId {
            println("nil")
            return nil;
        }
        
        // Same as previous sender, skip
        if indexPath.item > 0 {
            let previousMessage = messages[indexPath.item - 1];
            if previousMessage.senderId == message.senderId {
                println("nil")
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
            
            // get the message avatar url
            // thanks, mark!
            let fbUserId = 4;
            let imageUrl = "https://graph.facebook.com/v2.3/\(fbUserId)/picture" as String
            
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
        if message.senderId == senderId {
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
        if message.senderId == senderId {
            return CGFloat(0.0);
        }
        
        // Same as previous sender, skip
        if indexPath.item > 0 {
            let previousMessage = messages[indexPath.item - 1];
            if previousMessage.senderId == message.senderId {
                return CGFloat(0.0);
            }
        }
        
        return kJSQMessagesCollectionViewCellLabelHeightDefault
    }
    
    
    
    
    

}
