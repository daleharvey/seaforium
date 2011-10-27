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
	function get_latest()
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

		$sql .= $sql ? ' AND' : 'WHERE';
		$sql .= ' threads.deleted = 0';
		$sql = "
			SELECT
				name,last_comment_created
				FROM categories";
		return $this->db->query($sql);
	}

}

?>
