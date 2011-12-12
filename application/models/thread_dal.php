<?php

class Thread_dal extends Model
{
  function Thread()
  {
    parent::__construct();
  }

  /**
   * Insert a new thread into the database
   *
   * @param	array
   * @return	int
   */
  function new_thread($data)
  {
    $sql = "INSERT INTO threads (user_id, subject, category, created)
	    VALUES (?, ?, ?, ?)";

    $this->db->query($sql, array($data['user_id'], $data['subject'],
                                 $data['category'],date("Y-m-d H:i:s", utc_time())));

    return $this->db->insert_id();
  }

  /**
   * Get some threads from the database
   *
   * @return	int
   */
  function get_thread_count($sql)
  {
    $count = (int)$this->db->query('SELECT count(threads.thread_id) AS ' .
                                   'max_rows FROM threads ' . $sql)->row()->max_rows;

    return $count > 0 ? $count : '0';
  }

  /**
   * Get some threads from the database
   *
   * @param	int
   * @param	int
   * @return	object
   */
  function get_threads($user_id, $limit, $span, $filtering = '', $ordering = '')
  {
	
	/*$this->db->select('
		threads.subject,
		threads.created,
		threads.closed,
		threads.nsfw,
		threads.thread_id,
		threads.user_id,
		categories.name AS category,
		authors.username AS author_name,
		responders.username AS responder_name,
		responses.created AS response_created,
		IFNULL(acquaintances.type) AS acq,
		
		');*/
	
    $sql = "SELECT
	      threads.subject,
	      threads.created,
	      threads.closed,
	      threads.nsfw,
	      threads.thread_id,
              threads.user_id,
	      categories.name AS category,
	      authors.username AS author_name,
	      responders.username AS responder_name,
	      responses.created AS response_created,
	      IFNULL(acquaintances.type, 0) AS acq,
	      (
	        SELECT
		  count(comments.comment_id)
		FROM comments
		WHERE comments.thread_id = threads.thread_id
	      ) AS response_count
	      FROM threads
	      JOIN comments AS responses
	        ON responses.comment_id = threads.last_comment_id
	      JOIN users AS authors
	        ON threads.user_id = authors.id
	      JOIN users AS responders
	        ON responses.user_id = responders.id
	      LEFT JOIN categories
	        ON threads.category = categories.category_id
	      LEFT JOIN acquaintances
	      ON acquaintances.acq_user_id = authors.id AND acquaintances.user_id = ?
	      ". $filtering ."
	      ". $ordering ."
	      LIMIT ?, ?";

    return $this->db->query($sql, array($user_id, (int)$limit, (int)$span));
  }

  /**
   * Get user record by Id
   *
   * @param	int
   * @param	bool
   * @return	object
   */
  function get_thread_information($user_id, $thread_id)
  {
    $sql = "SELECT
	      threads.user_id,
	      subject,
	      closed,
	      nsfw,
	      created,
	      categories.name AS category,
	    IFNULL(acquaintances.type, 1) AS type
	    FROM threads
	    LEFT JOIN categories
	      ON threads.category = categories.category_id
	    LEFT JOIN acquaintances
	      ON acquaintances.user_id = threads.user_id
	    AND acquaintances.acq_user_id = ?
	    WHERE thread_id = ? AND threads.deleted != 1";

    return $this->db->query($sql, array($user_id, $thread_id));
  }

  /**
   * Insert a new comment into the database
   *
   * @param	array
   * @return	void
   */
  function new_comment($data)
  {
    $whattime = date("Y-m-d H:i:s", utc_time());
    $sql = "INSERT INTO comments (thread_id, user_id, content, original_content, "
      . "created) VALUES (?, ?, ?, ?, ?)";

    $this->db->query($sql, array($data['thread_id'], $data['user_id'],
                                 $data['content'], $data['original_content'],
                                 $whattime));

    $sql = "UPDATE threads SET last_comment_id = ?,last_comment_created = ? " .
      "WHERE thread_id = ?";

    $this->db->query($sql,
                     array($this->db->insert_id(), $whattime, $data['thread_id']));

    $sql = "UPDATE categories SET last_comment_created = ? WHERE " .
      "category_id = (SELECT category FROM threads WHERE thread_id = ?)";

    $this->db->query($sql, array($whattime,$data['thread_id']));
  }

  /**
   * Get a count of all the comments for a given thread id
   *
   * @param	string
   * @return	object
   */
  function comment_count($thread_id)
  {
    $sql = "SELECT count(comment_id) AS max_rows FROM comments WHERE thread_id = ?";
    return $this->db->query($sql, $thread_id)->row()->max_rows;
  }


  /**
   * Get a count of all the comments for a given thread id
   *
   * @param	string
   * @return	object
   */
  function comment_count_info($thread_id)
  {
    $sql = "SELECT threads.subject, count(comment_id) AS max_rows FROM threads " .
      "LEFT JOIN comments ON comments.thread_id = threads.thread_id  WHERE " .
      "threads.thread_id= ? LIMIT 1";
    return $this->db->query($sql, $thread_id)->row();
  }


  function is_first_comment($thread_id, $comment_id)
  {
    $sql = "SELECT comment_id FROM comments WHERE comments.thread_id = ? ORDER BY " .
      "comments.created LIMIT 1";
    return $this->db->query($sql, $thread_id)->row()->comment_id == $comment_id;
  }
  /**
   * Get a count of all the comments for a given thread id
   *
   * @param	int
   * @param	int
   * @param	int
   * @return	object
   */
  function get_comments($user_id, $thread_id, $start, $end)
  {
    $sql = "SELECT
	      comments.thread_id,
	      comments.comment_id,
	      comments.content,
	      comments.original_content,
	      comments.created,
	      comments.deleted,
	      comments.user_id,
	      users.username,
	      users.id,
	      users.emoticon,
	      acquaintances.type AS acq_type
	    FROM comments
	    LEFT JOIN users
	      ON comments.user_id = users.id
	    LEFT JOIN acquaintances
	      ON acquaintances.acq_user_id = users.id
	      AND acquaintances.user_id = ?
	    WHERE comments.thread_id = ?
	    ORDER BY comments.created
	    LIMIT ?, ?";

    return $this->db->query($sql, array($user_id, $thread_id, $start, $end));
  }

  function update_comment_cache($comment_id, $content)
  {
    $this->db->query("UPDATE comments SET content = ? WHERE comment_id = ?",
                     array($content, $comment_id));

    return $this->db->affected_rows() === 1;
  }

  /**
   * Get the content and author of a comment
   *
   * @param	int
   * @return	object
   */
  function get_comment($comment_id)
  {
    return $this->db->query("SELECT thread_id, created, content, original_content, user_id, " .
                            "created FROM comments WHERE comment_id = ?", $comment_id);
  }

  /**
   * Update a comment with new data
   *
   * @param	int
   * @param	string
   * @param	int
   * @return	bool
   */
  function update_comment($comment_id, $content, $processed, $user_id)
  {
    $this->db->query("UPDATE comments SET original_content = ?, content = ? WHERE " .
                     "comment_id = ? AND user_id = ?",
                     array($content, $processed, $comment_id, $user_id));

    return $this->db->affected_rows() === 1;
  }

  /**
   * Update the thread subject
   *
   * @param	int
   * @param	string
   * @param	int
   * @return	bool
   */
  function update_subject($thread_id, $subject, $user_id)
  {
    $this->db->query("UPDATE threads SET subject = ? WHERE thread_id = ? AND " .
                     "user_id = ? AND created > DATE_SUB(NOW(), INTERVAL 5 MINUTE)",
                     array($subject, $thread_id, $user_id));

    return $this->db->affected_rows() === 1;
  }

  /**
   * Get the current front page title
   *
   * @return	object
   */
  function get_front_title()
  {
    $result = $this->db->query("SELECT titles.title_text, users.username FROM " .
                               "titles LEFT JOIN users ON titles.author_id = " .
                               "users.id ORDER BY titles.title_id DESC LIMIT 1");

    return $result->num_rows === 1
      ? $result->row()
      : (object) array("title_text" => "Change Me, Please", "username" => "anon");
  }

  function get_participated_threads($user_id)
  {
    $qry = $this->db->query("SELECT GROUP_CONCAT(DISTINCT comments.thread_id) AS " .
                            "thread_ids FROM comments,threads WHERE " .
                            "comments.user_id = ? AND comments.thread_id = " .
                            "threads.thread_id AND threads.deleted = 0",
                            $user_id)->row()->thread_ids;
    return strlen($qry) > 0 ? $qry : '0';
  }

  function get_started_threads($user_id)
  {
    $started = $this->db->query("SELECT GROUP_CONCAT(DISTINCT thread_id) AS " .
                                "thread_ids FROM threads WHERE user_id = ? AND " .
                                "deleted = 0", $user_id)->row()->thread_ids;
    return strlen($started) > 0 ? $started : '0';
  }

  function change_nsfw($user_id, $thread_id, $status)
  {
    $this->db->query("UPDATE threads SET nsfw = ? WHERE thread_id = ? AND " .
                     "user_id = ? LIMIT 1", array($status, $thread_id, $user_id));

    return $this->db->affected_rows();
  }

  function change_closed($user_id, $thread_id, $status)
  {
    $this->db->query("UPDATE threads SET closed = ? WHERE thread_id = ? AND " .
                     "user_id = ? LIMIT 1", array($status, $thread_id, $user_id));

    return $this->db->affected_rows();
  }

  function change_deleted($user_id, $thread_id, $status)
  {
    if ($status != 1) {
      return 0;
    }

    $this->db->query("UPDATE threads SET deleted = ? WHERE thread_id = ? AND " .
                     "user_id = ? AND created > DATE_SUB(NOW(), INTERVAL 5 MINUTE) " .
                     "LIMIT 1", array($status, $thread_id, $user_id));

    return $this->db->affected_rows();
  }

  function get_favorites($user_id)
  {
    $fav = $this->db->query("SELECT GROUP_CONCAT(favorites.thread_id) AS favorites " .
                            "FROM favorites,threads WHERE favorites.user_id = ? " .
                            "AND favorites.thread_id = threads.thread_id AND " .
                            "threads.deleted = 0", $user_id)->row()->favorites;

    return strlen($fav) > 0 ? $fav : '0';
  }

  function add_favorite($favorite_id, $user_id, $thread_id)
  {
    $this->db->query("INSERT INTO favorites (favorite_id, user_id, thread_id) " .
                     "VALUES (?, ?, ?)", array($favorite_id, $user_id, $thread_id));

    return $this->db->affected_rows();
  }

  function remove_favorite($favorite_id)
  {
    $this->db->query("DELETE FROM favorites WHERE favorite_id = ?", $favorite_id);

    return $this->db->affected_rows();
  }

  function get_hidden($user_id)
  {
    $hidden = $this->db->query("SELECT GROUP_CONCAT(hidden_threads.thread_id) AS hidden_threads " .
                            "FROM hidden_threads,threads WHERE hidden_threads.user_id = ? " .
                            "AND hidden_threads.thread_id = threads.thread_id AND " .
                            "threads.deleted = 0", $user_id)->row()->hidden_threads;

    return strlen($hidden) > 0 ? $hidden : '0';
  }

  function add_hide_thread($hide_id, $user_id, $thread_id)
  {
    $this->db->query("INSERT INTO hidden_threads (hidden_id, user_id, thread_id) ".
					 "VALUES (?, ?, ?)", array($hide_id, $user_id, $thread_id));

	return $this->db->affected_rows();
  }

  function remove_hide_thread($hide_id)
  {
    $this->db->query("DELETE FROM hidden_threads WHERE hidden_id = ?", $hide_id);

	return $this->db->affected_rows();
  }

  function find_thread_by_title($user_id, $limit, $span, $filtering = '',
                                $ordering = '', $search_phrase)
  {
  $search_phrase = "%" . $search_phrase . "%";
    $sql = "SELECT
	      threads.subject,
	      threads.created,
	      threads.nsfw,
	      threads.thread_id,
	      threads.user_id,
              threads.closed,
	      categories.name AS category,
	      authors.username AS author_name,
	      authors.username AS author_name,
	      responders.username AS responder_name,
	      responses.created AS response_created,
	      IFNULL(acquaintances.type, 0) AS acq,
	     (
	       SELECT
	         count(comments.comment_id)
	       FROM comments
	       WHERE comments.thread_id = threads.thread_id
	     ) AS response_count
	   FROM threads
	   JOIN comments AS responses
	     ON responses.comment_id = threads.last_comment_id
	   JOIN users AS authors
	     ON threads.user_id = authors.id
	   JOIN users AS responders
	     ON responses.user_id = responders.id
	   LEFT JOIN categories
	     ON threads.category = categories.category_id
	   LEFT JOIN acquaintances
	     ON acquaintances.acq_user_id = authors.id AND acquaintances.user_id = ?
	   WHERE threads.subject LIKE '" . $search_phrase .
                                                "'
           ". $filtering ."
	   AND threads.deleted = 0
	   ". $ordering ."
           LIMIT ?, ?";

    return $this->db->query($sql, array($user_id, (int)$limit, (int)$span));
  }

  function find_thread_by_title_rows($search_phrase)
  {
	$search_phrase = "%" . $search_phrase . "%";
    $this->db->query("SELECT * FROM `threads` WHERE subject LIKE ?",
                     $search_phrase);
    return $this->db->affected_rows();
  }
}