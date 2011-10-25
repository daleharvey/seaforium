
ALTER TABLE  `comments` CHANGE  `content`  `content` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;

ALTER TABLE  `titles` CHANGE  `title_text`  `title_text` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;

ALTER TABLE  `threads` CHANGE  `subject`  `subject` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
