<?php

class Thread_dal extends Model
{
	function Thread()
	{
		parent::__construct();
	}
	
	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_thread_information($thread_id)
	{
		$sql = "SELECT subject FROM threads WHERE thread_id = ?"; 
		
		return $this->db->query($sql, $thread_id);
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
			$this->session->userdata('user_id'),
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
	function get_comments($thread_id, $limit_start, $limit_end)
	{
		
		$sql = "
			SELECT
				comments.comment_id,
				comments.content,
				comments.created,
				comments.deleted,
				comments.user_id,
				users.username
			FROM comments
			LEFT JOIN users
				ON comments.user_id = users.id
			WHERE comments.thread_id = ?
			ORDER BY comments.created
			LIMIT ?, ?";
		
		return $this->db->query($sql, array(
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
	
	function update_comment($comment_id, $content, $user_id)
	{
		$this->db->query("UPDATE comments SET content = ? WHERE comment_id = ? AND user_id = ?", array(
			$content,
			$comment_id,
			$user_id
		));
		
		return $this->db->affected_rows() === 1;
	}
}