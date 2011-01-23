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
		$sql = "
			INSERT INTO threads
				(user_id, subject, category, created)
			VALUES
				(?, ?, ?, NOW())";
		
		$this->db->query($sql, array(
			$data['user_id'],
			$data['subject'],
			$data['category']
		));
		
		return $this->db->insert_id();
	}
	
	/**
	 * Get some threads from the database
	 *
	 * @return	int
	 */
	function get_thread_count($sql)
	{
		return (int)$this->db->query('SELECT count(threads.thread_id) AS max_rows FROM threads '.$sql)->row()->max_rows;
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
		
		$sql = "
			SELECT
				threads.subject,
				threads.created,
				threads.nsfw,
				threads.thread_id,
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
			". $filtering ."
			". $ordering ."
			LIMIT ?, ?";
		
		return $this->db->query($sql, array(
			$user_id,
			(int)$limit,
			(int)$span
		));
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
		$sql = "
			SELECT 
				subject, 
				closed, 
				nsfw, 
				categories.name AS category, 
				IFNULL(acquaintances.type, 1) AS type
			FROM threads 
			LEFT JOIN categories 
				ON threads.category = categories.category_id 
			LEFT JOIN acquaintances
				ON acquaintances.user_id = threads.user_id
				AND acquaintances.acq_user_id = ?
			WHERE thread_id = ?"; 
		
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
		$sql = "INSERT INTO comments (thread_id, user_id, content, created) VALUES (?, ?, ?, NOW())";
		
		$this->db->query($sql, array(
			$data['thread_id'],
			$data['user_id'],
			$data['content']
		));
		
		$sql = "UPDATE threads SET last_comment_id = ? WHERE thread_id = ?";
		
		$this->db->query($sql, array(
			$this->db->insert_id(),
			$data['thread_id']
		));
		
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
	 * @param	int
	 * @param	int
	 * @param	int
	 * @return	object
	 */
	function get_comments($user_id, $thread_id, $limit_start, $limit_end)
	{
		
		$sql = "
			SELECT
				comments.thread_id,
				comments.comment_id,
				comments.content,
				comments.created,
				comments.deleted,
				comments.user_id,
				users.username,
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
		
		return $this->db->query($sql, array(
			$user_id,
			$thread_id,
			$limit_start,
			$limit_end
		));
	}
	
	/**
	 * Get the content and author of a comment
	 *
	 * @param	int
	 * @return	object
	 */
	function get_comment($comment_id)
	{
		return $this->db->query("SELECT content, user_id, created FROM comments WHERE comment_id = ?", $comment_id);
	}
	
	/**
	 * Update a comment with new data
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function update_comment($comment_id, $content, $user_id)
	{
		$this->db->query("UPDATE comments SET content = ? WHERE comment_id = ? AND user_id = ?", array(
			$content,
			$comment_id,
			$user_id
		));
		
		return $this->db->affected_rows() === 1;
	}
	
	/**
	 * Get the current front page title
	 *
	 * @return	object
	 */
	function get_front_title()
	{
		$result = $this->db->query("SELECT titles.title_text, users.username FROM titles LEFT JOIN users ON titles.author_id = users.id ORDER BY titles.title_id DESC LIMIT 1");
		
		return $result->num_rows === 1
			? $result->row()
			: (object) array("title_text" => "Change Me, Please", 
                                      "username" => "anon");
	}
	
	function get_participated_threads($user_id)
	{
		return $this->db->query("SELECT GROUP_CONCAT(DISTINCT thread_id) AS thread_ids FROM comments WHERE user_id = ?", $user_id)->row()->thread_ids;
	}
	
	function change_nsfw($user_id, $thread_id, $status)
	{
		$this->db->query("UPDATE threads SET nsfw = ? WHERE thread_id = ? AND user_id = ? LIMIT 1", array(
			$status,
			$thread_id,
			$user_id
		));
		
		return $this->db->affected_rows();
	}
	
	function change_closed($user_id, $thread_id, $status)
	{
		$this->db->query("UPDATE threads SET closed = ? WHERE thread_id = ? AND user_id = ? LIMIT 1", array(
			$status,
			$thread_id,
			$user_id
		));
		
		return $this->db->affected_rows();
	}
	
	function get_favorites($user_id)
	{
		$favorites = $this->db->query("SELECT GROUP_CONCAT(thread_id) AS favorites FROM favorites WHERE user_id = ?", $user_id)->row()->favorites;
		return strlen($favorites) > 0 ? $favorites : '0';
	}
	
	function add_favorite($favorite_id, $user_id, $thread_id)
	{
		$this->db->query("INSERT INTO favorites (favorite_id, user_id, thread_id) VALUES (?, ?, ?)", array($favorite_id, $user_id, $thread_id));
		
		return $this->db->affected_rows();
	}
	
	function remove_favorite($favorite_id)
	{
		$this->db->query("DELETE FROM favorites WHERE favorite_id = ?", $favorite_id);
		
		return $this->db->affected_rows();
	}
}