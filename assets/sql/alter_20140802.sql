CREATE TABLE `point` (
   `id` int(11) not null auto_increment,
   `giver` int(11) not null,
   `receiver` int(11) not null,
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `review` (
   `id` int(11) not null auto_increment,
   `giver` int(11) not null,
   `receiver` int(11) not null,
   `review` text,
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
