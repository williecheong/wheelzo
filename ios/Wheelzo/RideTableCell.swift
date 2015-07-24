//
//  RideTableCell.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-06-25.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import UIKit

class RideTableCell: UITableViewCell {
    
    // @IBOutlet weak var profilePic: UIImageView!
    
    @IBOutlet weak var toLabel: UILabel!
    @IBOutlet weak var fromLabel: UILabel!
    @IBOutlet weak var dateLabel: UILabel!
    @IBOutlet weak var dayLabel: UILabel!
    @IBOutlet weak var timeLabel: UILabel!
    @IBOutlet weak var priceLabel: UILabel!
    
    var rideData : NSDictionary = NSDictionary();
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }
    
    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
}