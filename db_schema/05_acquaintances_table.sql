CREATE TABLE `acquaintances` (
  `acq_id` varchar(32) NOT NULL,
  `user_id` int(7) NOT NULL,
  `acq_user_id` int(7) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`acq_id`),
  KEY `K_acq_main_user` (`user_id`,`acq_user_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;