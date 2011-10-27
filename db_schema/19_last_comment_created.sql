ALTER TABLE threads 
ADD COLUMN last_comment_created datetime NOT NULL;
ALTER TABLE categories 
ADD COLUMN last_comment_created datetime NOT NULL;

UPDATE threads
SET last_comment_created=(SELECT comments.created
FROM comments
WHERE comments.comment_id = threads.last_comment_id);

UPDATE categories
SET last_comment_created=(SELECT threads.last_comment_created
FROM threads
WHERE categories.category_id = threads.category
ORDER BY threads.created desc
LIMIT 0, 1);
