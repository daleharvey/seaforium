ALTER TABLE `user_profiles` ADD CONSTRAINT `FK_user_profiles_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE `acquaintances`  ADD CONSTRAINT `FK_acquaintances_acq_users` FOREIGN KEY (`acq_user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE `pm_inbox`  ADD CONSTRAINT `FK_pm_inbox_to_id` FOREIGN KEY (`to_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `pm_inbox`  ADD CONSTRAINT `FK_pm_inbox_from_id` FOREIGN KEY (`from_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `pm_inbox`  ADD CONSTRAINT `FK_pm_inbox_message_id` FOREIGN KEY (`message_id`) REFERENCES `pm_content` (`message_id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE `pm_outbox`  ADD CONSTRAINT `FK_pm_outbox_to_id` FOREIGN KEY (`to_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `pm_outbox`  ADD CONSTRAINT `FK_pm_outbox_from_id` FOREIGN KEY (`from_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `pm_outbox`  ADD CONSTRAINT `FK_pm_outbox_message_id` FOREIGN KEY (`message_id`) REFERENCES `pm_content` (`message_id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE  `threads` CHANGE  `category`  `category` INT( 2 ) NULL DEFAULT  '0';
ALTER TABLE `threads`  ADD CONSTRAINT `FK_threads_category` FOREIGN KEY (`category`) REFERENCES `categories` (`category_id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
