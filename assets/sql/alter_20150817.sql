ALTER TABLE `user` ENGINE = MYISAM;
ALTER TABLE `user` ADD FULLTEXT `name` (`name`);

ALTER TABLE `ride` ADD INDEX `start` (`start`);
ALTER TABLE `ride` ADD INDEX `driver_id` (`driver_id`);

ALTER TABLE `user_ride` ADD INDEX `user_id` (`user_id`);
ALTER TABLE `user_ride` ADD INDEX `ride_id` (`ride_id`);

ALTER TABLE `comment` ADD INDEX `user_id` (`user_id`);
ALTER TABLE `comment` ADD INDEX `ride_id` (`ride_id`);

ALTER TABLE `rrequest` ENGINE = MYISAM;
ALTER TABLE `rrequest` ADD INDEX `user_id` (`user_id`);
ALTER TABLE `rrequest` ADD FULLTEXT `origin` (`origin`);
ALTER TABLE `rrequest` ADD FULLTEXT `destination` (`destination`);
ALTER TABLE `rrequest` ADD INDEX `start` (`start`);

ALTER TABLE `point` ADD INDEX `giver_id` (`giver_id`);
ALTER TABLE `point` ADD INDEX `receiver_id` (`receiver_id`);

ALTER TABLE `review` ADD INDEX `giver_id` (`giver_id`);
ALTER TABLE `review` ADD INDEX `receiver_id` (`receiver_id`);

ALTER TABLE `facebook_ride` ADD INDEX `ride_id` (`ride_id`);
ALTER TABLE `facebook_ride` ADD INDEX `facebook_post_id` (`facebook_post_id`);

