CREATE TABLE `facebook_ride` (
   `id` int(11) not null auto_increment,
   `ride_id` int(11) not null,
   `facebook_post_id` varchar(255) not null,
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;