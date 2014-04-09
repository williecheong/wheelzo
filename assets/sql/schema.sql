CREATE TABLE `user` (
   `id` int(11) not null auto_increment,
   `email` varchar(255) not null,
   `facebook_id` varchar(255) not null,
   `cell_number` varchar(255),
   `rating` varchar(255),
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`),
   UNIQUE KEY (`facebook_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `ride` (
   `id` int(11) not null auto_increment,
   `driver_id` int(11) not null,
   `origin` varchar(255) not null,
   `destination` varchar(255) not null,
   `pickup` varchar(255) not null,
   `capacity` int(11) not null default 1,
   `price` int(11) not null default 10,
   `start` datetime not null,
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `user_ride` (
   `id` int(11) not null auto_increment,
   `user_id` int(11) not null,
   `ride_id` int(11) not null,
   `passenger_rating` varchar(255),
   `driver_rating` varchar(255),
   `last_updated` timestamp default current_timestamp on update current_timestamp,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


INSERT INTO `user` (`id`, `email`, `facebook_id`, `cell_number`) VALUES
('1',    'maksym.p@gmail.com',           'max.pikhteryev',     '5195897412'),
('2',    'cheongwillie@hotmail.com',     'willie.cheong.10',   '5197211674');


INSERT INTO `ride` (`id`, `driver_id`, `origin`, `destination`, `pickup`, `capacity`, `price`, `start`) VALUES
('1',    '1', 'Waterloo',        'Toronto',        'UW DC',        '2',  '10',    '2014-03-16 00:00:00' ),
('2',    '1', 'Toronto',         'Waterloo',       'Yorkdale',     '2',  '10',    '2014-03-18 00:00:00' ),
('3',    '2', 'Waterloo',        'Mississauga',    'UW DC',        '3',  '8',     '2014-04-18 00:00:00' ),
('4',    '2', 'Mississauga',     'Waterloo',       'Square One',   '4',  '8',     '2014-03-23 00:00:00' );

