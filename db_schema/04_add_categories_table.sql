CREATE TABLE `categories` (
  `category_id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `categories`(`category_id`,`name`) VALUES (1,'Discussions'),(2,'Projects'),(3,'Advice'),(4,'Meaningless');