ALTER TABLE users 
ADD COLUMN threads_count INT NOT NULL DEFAULT '0',
ADD COLUMN comments_count INT NOT NULL DEFAULT '0';

UPDATE users
SET threads_count=(SELECT COUNT(threads.thread_id)
FROM threads
WHERE threads.user_id = users.id),
comments_count=(SELECT COUNT(comments.thread_id)
FROM comments
WHERE comments.user_id = users.id);