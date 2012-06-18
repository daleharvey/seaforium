<?php

class Thread_model extends Model
{
	var $meta, $return, $page;

	public function get_thread($thread_id, $meta, $page)
	{
		if (!is_numeric($thread_id))
			return NULL;

		$this->page = $page;

		// set the internal happiness
		$this->meta = $meta;

		// get thread information from the database
		$this->return = (object) array(
			'information' => $this->get_information($thread_id)
		);

		// if the thread info is null, no need to continue
		if ($this->return->information == NULL)
			return NULL;

		// get the comments from the database
		$this->return->comments = $this->get_comments($thread_id);

		// has to be called DIRECTLY AFTER $this->get_comments()
		$this->return->information->comment_count = (int) $this->db->query('SELECT FOUND_ROWS() AS thread_count')->row()->thread_count;

		// send it back
		return $this->return;
	}

	/*
   * Gets basic header information for a given thread
   *
   * @param	int
	 */
	private function get_information($thread_id)
	{
		$sql = "SELECT
				threads.user_id AS author_id,
				subject,
				closed,
				nsfw,
				created,
				categories.name AS category,
				IFNULL(acquaintances.type, 1) AS author_acquaintance_type,
				!ISNULL(favorites.favorite_id) AS favorite,
				!ISNULL(hidden_threads.hidden_id) AS hidden
			FROM threads
			LEFT JOIN categories
				ON threads.category = categories.category_id
			LEFT JOIN acquaintances
				ON acquaintances.user_id = threads.user_id
				AND acquaintances.acq_user_id = ?
			LEFT JOIN favorites
				ON favorites.user_id = ?
				AND favorites.thread_id = threads.thread_id
			LEFT JOIN hidden_threads
				ON hidden_threads.user_id = ?
				AND hidden_threads.thread_id = threads.thread_id
			WHERE threads.thread_id = ? AND threads.deleted != 1";

		$result = $this->db->query($sql, array(
				$this->meta['user_id'],
				$this->meta['user_id'],
				$this->meta['user_id'],
				$thread_id)
			)->row();

		if (count($result))
		{
			return (object) array(
				'thread_id' => $thread_id,
				'author_id' => (int) $result->author_id,
				'subject' => $result->subject,
				'closed' => (bool) $result->closed,
				'nsfw' => (bool) $result->nsfw,
				'created' => $result->created,
				'category' => $result->category,
				'author_acquaintance_type' => (int) $result->author_acquaintance_type,
				'favorite' => (bool) $result->favorite,
				'hidden' => (bool) $result->hidden,
				'editable' => time() - strtotime($result->created) < 300,
				'enemies' => 0,
				'owner' => $this->meta['user_id'] == $result->author_id
			);
		} else {
			return NULL;
		}
	}

  /**
   * Get a count of all the comments for a given thread id
   *
   * @param	int
   * @param	int
   */
  private function get_comments($thread_id)
  {
    $sql = "SELECT
				SQL_CALC_FOUND_ROWS
	      comments.comment_id,
	      comments.content,
	      comments.original_content,
	      comments.created,
	      comments.deleted,
	      comments.user_id AS author_id,
	      users.username AS author_name,
	      users.banned AS author_banned,
	      users.emoticon,
	      IFNULL(acquaintances.type, 0) AS author_acquaintance_type
	    FROM comments
	    LEFT JOIN users
	      ON comments.user_id = users.id
	    LEFT JOIN acquaintances
	      ON acquaintances.acq_user_id = users.id
	      AND acquaintances.user_id = ?
	    WHERE comments.thread_id = ?
	    ORDER BY comments.created
	    LIMIT ?, ?";

		$result = $this->db->query($sql, array(
			$this->meta['user_id'],
			$thread_id,
			$this->page,
			$this->meta['comments_shown']
			));

		if (!$result->num_rows())
		{
			return NULL;
		} else {
			$return = array();

			$i = 0;
			foreach($result->result() as $row)
			{
				if ($row->deleted == '0')
				{
					$return[$i] = (object) array(
						'comment_id' => $row->comment_id,
						'content' => $row->content,
						'created' => strtotime($row->created),
						'deleted' => 0,
						'author_id' => (int) $row->author_id,
						'author_name' => $row->author_name,
						'author_banned' => (bool) $row->author_banned,
						'url_safe_author_name' => url_title($row->author_name, 'dash'),
						'emoticon' => (bool) $row->emoticon,
						'author_acquaintance_type' => (int) $row->author_acquaintance_type,
						'author_acquaintance_name' => $row->author_acquaintance_type == 1 ? 'buddy' : ($row->author_acquaintance_type == 2 ? 'enemy' : NULL),
						'owner' => $this->meta['user_id'] == $row->author_id,
						'editable' => ($this->meta['user_id'] == $row->author_id) && ($row->created < time() - (60 * 60 * 24)),
						'show_controls' => $this->page == 0 && ($this->meta['user_id'] == $row->author_id) && !$i
					);

					// update comments if the content doesnt match original
					if ($row->content === '' && $row->content == _process_post($row->original_content))
					{
						$return[$i]->content = _process_post($row->original_content);
						$this->db->query("UPDATE comments SET content = ? WHERE comment_id = ?",
                     array($return[$i]->content, $row->comment_id));
					}

					if ($return[$i]->author_acquaintance_type == 2)
						++$this->return->information->enemies;
				} else {
					$return[$i] = (object) array(
						'comment_id' => (int) $row->comment_id,
						'created' => $row->created,
						'deleted' => 1
					);
				}

				++$i;
			}

			return $return;
		}
  }

  /**
   * Insert a new comment into the database
   *
   * @param	array
   */
  function new_comment($comment)
  {
    $created = date("Y-m-d H:i:s", utc_time());

    $sql = "INSERT
				INTO comments (
					thread_id,
					user_id,
					content,
					original_content,
					created
				) VALUES (?, ?, ?, ?, ?)";

    $this->db->query($sql,
			array(
				$comment->thread_id,
				$comment->user_id,
				$comment->content,
				$comment->original_content,
				$created
			)
		);

    $sql = "UPDATE
				threads
			SET
				last_comment_id = ?,
				last_comment_created = ?
			WHERE thread_id = ?";

    $this->db->query($sql,
			array(
				$this->db->insert_id(),
				$created,
				$comment->thread_id
			)
		);

    $sql = "UPDATE
				categories
			SET
				last_comment_created = ?
			WHERE category_id = (
				SELECT category
				FROM threads
				WHERE thread_id = ?)";

    $this->db->query($sql,
			array(
				$created,
				$comment->thread_id
			)
		);
  }
}