CREATE TABLE `ip_addresses` (
  `address_id` varchar(32) NOT NULL,
  `user_id` int(8) NOT NULL,
  `ip_address` varchar(45),
  `created` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;