CREATE TABLE `pm_content` (
  `message_id` int(7) NOT NULL AUTO_INCREMENT,
  `subject` varchar(32) NOT NULL,
  `content` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `pm_inbox` (
  `inbox_id` int(7) NOT NULL AUTO_INCREMENT,
  `to_id` int(7) NOT NULL,
  `from_id` int(7) NOT NULL,
  `message_id` int(7) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`inbox_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `pm_outbox` (
  `outbox_id` int(7) NOT NULL AUTO_INCREMENT,
  `to_id` int(7) NOT NULL,
  `from_id` int(7) NOT NULL,
  `message_id` int(7) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`outbox_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;