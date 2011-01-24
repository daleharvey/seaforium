CREATE TABLE `favorites` (
  `favorite_id` varchar(32) NOT NULL,
  `user_id` int(7) NOT NULL,
  `thread_id` int(7) NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;