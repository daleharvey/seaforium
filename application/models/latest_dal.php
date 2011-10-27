<?php

class Latest_dal extends Model
{
	function Latest()
	{
		parent::__construct();
	}

	/**
	 * Get some threads from the database
	 *
	 * @return	int
	 */
	function get_latest($filter)
	{
	    switch(strtolower($filter))
		{
			case 'discussions':
				$sql = "WHERE threads.category = 1";
				break;
			case 'projects':
				$sql = "WHERE threads.category = 2";
				break;
			case 'advice':
				$sql = "WHERE threads.category = 3";
				break;
			case 'meaningless':
				$sql = "WHERE threads.category = 4";
				break;
			default:
				$sql = '';
		}

		$sql = "
			SELECT
				responses.created AS response_created
				FROM threads
				JOIN comments AS responses
					ON responses.comment_id = threads.last_comment_id
				".$sql."
				ORDER BY threads.created desc
				LIMIT 0, 1";
		$query = $this->db->query($sql);
		if ($query->num_rows() === 1) {
			return $query->row()->response_created;
		}

		return FALSE;
	}

}