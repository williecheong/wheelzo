//
//  CommentsTableCell.swift
//  Wheelzo
//
//  Created by Maksym Pikhteryev on 2015-06-27.
//  Copyright (c) 2015 Maksym Pikhteryev. All rights reserved.
//

import UIKit

class CommentsTableCell: UITableViewCell {
    
    @IBOutlet weak var profilePic: UIImageView!
    @IBOutlet weak var nameLabel: UILabel!
    @IBOutlet weak var dateLabel: UILabel!
    @IBOutlet weak var commentLabel: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        
    }
    
    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
}