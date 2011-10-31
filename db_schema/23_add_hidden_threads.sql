CREATE TABLE `hidden_threads` (
  `hidden_id` varchar(32) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `thread_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`hidden_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;