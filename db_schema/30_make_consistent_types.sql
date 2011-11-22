ALTER TABLE `user_profiles` DROP FOREIGN KEY `FK_user_profiles_users`;
ALTER TABLE  `user_profiles` CHANGE  `id` `id` int(8) NOT NULL AUTO_INCREMENT;
ALTER TABLE  `user_profiles` CHANGE  `user_id`  `user_id` int(8) NOT NULL;

ALTER TABLE  `users` CHANGE  `id`  `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE  `acquaintances` CHANGE  `user_id`  `user_id` int(8) NOT NULL;
ALTER TABLE  `acquaintances` CHANGE  `acq_user_id`  `acq_user_id` int(8) NOT NULL;

ALTER TABLE `user_profiles` ADD CONSTRAINT `FK_user_profiles_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE  `comments` CHANGE  `user_id`  `user_id` int(8) NOT NULL;
ALTER TABLE  `favorites` CHANGE  `user_id`  `user_id` int(8) NOT NULL;
ALTER TABLE  `hidden_threads` CHANGE  `user_id`  `user_id` int(8) NOT NULL;

ALTER TABLE  `pm_inbox` CHANGE  `to_id`  `to_id` int(8) NOT NULL;
ALTER TABLE  `pm_inbox` CHANGE  `from_id`  `from_id` int(8) NOT NULL;
ALTER TABLE  `pm_outbox` CHANGE  `to_id`  `to_id` int(8) NOT NULL;
ALTER TABLE  `pm_outbox` CHANGE  `from_id`  `from_id` int(8) NOT NULL;

ALTER TABLE  `sessions` CHANGE  `user_id`  `user_id` int(8) DEFAULT '0';

ALTER TABLE  `threads` CHANGE  `user_id`  `user_id` int(8) NOT NULL;
ALTER TABLE  `threads` CHANGE  `last_comment_id`  `last_comment_id` int(8) NOT NULL DEFAULT '0';

ALTER TABLE  `titles` CHANGE  `author_id`  `author_id` int(8) NOT NULL;

ALTER TABLE  `user_autologin` CHANGE  `user_id`  `user_id` int(8) NOT NULL;

ALTER TABLE  `users` CHANGE  `password`  `password` varchar(72) COLLATE utf8_bin NOT NULL;

ALTER TABLE  `login_attempts` CHANGE  `ip_address` `ip_address` varchar(16) COLLATE utf8_bin NOT NULL;
ALTER TABLE  `user_autologin` CHANGE  `last_ip` `last_ip` varchar(16) COLLATE utf8_bin NOT NULL;
ALTER TABLE  `users` CHANGE  `last_ip` `last_ip` varchar(16) COLLATE utf8_bin NOT NULL;

ALTER TABLE `sessions` ADD INDEX sessions_user_id (user_id);
ALTER TABLE `users` ADD INDEX users_username (username);