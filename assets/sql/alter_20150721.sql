ALTER TABLE user
ADD balance varchar(255);

ALTER TABLE user_ride
ADD transaction varchar(255);

DELETE * FROM user_ride;