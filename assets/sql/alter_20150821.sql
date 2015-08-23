CREATE TABLE `fbgroup` (
   `id` int(11) not null auto_increment,
   `name` varchar(255) not null,
   `facebook_id` varchar(255) not null,
   `introduced_by` int(11) not null default 1,
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `user_fbgroup` (
   `id` int(11) not null auto_increment,
   `user_id` int(11) not null,
   `fbgroup_id` int(11) not null,
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `fbgroup` ENGINE = MYISAM;
ALTER TABLE `fbgroup` ADD FULLTEXT `name` (`name`);
ALTER TABLE `fbgroup` ADD INDEX `facebook_id` (`facebook_id`);

ALTER TABLE `user_fbgroup` ADD INDEX `user_id` (`user_id`);
ALTER TABLE `user_fbgroup` ADD INDEX `fbgroup_id` (`fbgroup_id`);

INSERT INTO `fbgroup` (`facebook_id`, `name`) VALUES 
("372772186164295", "University of Waterloo Carpool"),
("453970331348083", "Montreal-Toronto rideshare"),
("231943393631223", "Rideshare Wilfred Laurier"),
("30961982319", "RIDESHARE Queen's University"),
("407883332557790", "Boston-Montreal Rideshare"),
("227191854109597", "Carpool Toronto-Ottawa-Montreal-Sherbrooke-Quebec-covoiturage");
