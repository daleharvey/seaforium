ALTER TABLE `users` DROP COLUMN `new_password_key`;
ALTER TABLE `users` DROP COLUMN `new_password_requested`;

ALTER TABLE `users` DROP COLUMN `new_email`;
ALTER TABLE `users` DROP COLUMN `new_email_key`;

ALTER TABLE `users` DROP COLUMN `yh_username`;
ALTER TABLE `users` DROP COLUMN `invited_by`;