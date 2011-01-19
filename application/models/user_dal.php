<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_dal extends Model
{
	function User_dal()
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
	function get_user_by_id($user_id)
	{
		$query = $this->db->query("SELECT * FROM users WHERE id = ?", $user_id);
		
		if ($query->num_rows() == 1)
			return $query->row();
		
		return NULL;
	}
	
	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
		$query = $this->db->query("SELECT * FROM users WHERE LOWER(username) = ?", strtolower($username));
		
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	
	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_id_by_username($username)
	{
		$query = $this->db->query("SELECT id FROM users WHERE LOWER(username) = ?", strtolower($username));
		
		if ($query->num_rows() == 1) return $query->row()->id;
		return FALSE;
	}
	
	function get_user_ids_from_array($usernames)
	{
		$usernames = array_map('strtolower', $usernames);
		$usernames = array_map('trim', $usernames);
		
		$userlist = "'". implode("','",$usernames) ."'";
		
		$sql = "SELECT id FROM users WHERE LOWER(username) IN (". $userlist .")";
		
		return $this->db->query($sql);
	}
	
	/**
	 * Get yh username by invite id
	 *
	 * @param	string
	 * @return	object
	 */
	function get_yh_username_by_invite($invite_id)
	{
		$query = $this->db->query("SELECT yh_username FROM yh_invites WHERE invite_id = ?", $invite_id);
		
		if ($query->num_rows() == 1) return $query->row()->yh_username;
		return NULL;
	}
	
	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$query = $this->db->query("SELECT 1 FROM users WHERE LOWER(username) = ?", strtolower($username));
		
		return $query->num_rows() == 0;
	}
	
	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$query = $this->db->query("SELECT 1 FROM users WHERE LOWER(email) = ?", strtolower($email));
		
		return $query->num_rows() == 0;
	}
	
	/**
	 * Check if yh username available for inviting
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_yh_username_available($username)
	{
		$query = $this->db->query("SELECT 1 FROM yh_invites WHERE LOWER(yh_username) = ?", strtolower($username));
		
		return $query->num_rows() == 0;
	}
	
	/**
	 * Check if yh invite is used
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_yh_invite_used($key)
	{
		$query = $this->db->query("SELECT 1 FROM yh_invites WHERE invite_id = ? AND used = 0", $key);
		
		return $query->num_rows() == 0;
	}
	
	/**
	 * Create new invite for yh user
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function create_yh_invite($username, $invite_id)
	{
		$this->db->query("INSERT INTO yh_invites (invite_id, yh_username, created) VALUES (?, ?, NOW())", array(
			$invite_id,
			$username
		));
		
		return $this->db->insert_id() != 0;
	}
	
	/**
	 * Get the whitelist from the database
	 *
	 * @return	array
	 */
	function get_yh_whitelist()
	{
		$list = array();
		
		foreach($this->db->query("SELECT * FROM yh_whitelist")->result() as $row)
		{
			$list[] = strtolower($row->username);
		}
		
		return $list;
	}
	
	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	 
	 // username email password last_ip key
	function create_user($data, $invite_id)
	{
		
		$sql = "
			INSERT INTO users (
				username,
				email,
				password,
				last_ip,
				yh_username,
				created,
				activated
			) VALUES (
				?, ?, ?, ?, ?, NOW(), 1
			)";
		
		$this->db->query($sql, array(
			$data['username'],
			$data['email'],
			$data['password'],
			$data['last_ip'],
			$data['yh_username']
		));
		
		if ($user_id = $this->db->insert_id())
		{
			$this->set_yh_invite_used($invite_id);
			$this->create_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Set yh invite key as used
	 *
	 * @param	string
	 * @return	void
	 */
	private function set_yh_invite_used($invite_id)
	{
		$this->db->query("UPDATE yh_invites SET used = 1 WHERE invite_id = ?", $invite_id);
	}
	
	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id)
	{
		return $this->db->query("INSERT INTO user_profiles (user_id) VALUES (?)", $user_id);
	}
	
	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$this->db->query("DELETE FROM users WHERE UNIX_TIMESTAMP(created) < ?", time() - $expire_period);
	}
	
	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @return	void
	 */
	function update_login_info($user_id)
	{
		$sql = "
			UPDATE users
			SET
				new_password_key = NULL,
				new_password_key = NULL,
				last_ip = ?,
				last_login = NOW()
			WHERE id = ?";
		
		$this->db->query($sql, array(
			$this->input->ip_address(),
			$user_id
		));
	}
	
	/**
	 * Pretty self-explanatory
	 *
	 * @param	string
	 * @return	object
	 */
	function get_profile_information($username)
	{
		$sql = "
			SELECT 
				users.id, 
				users.username, 
				users.created, 
				users.last_login,
				users.comments_shown,
				count(DISTINCT comments.comment_id) AS comment_count,
				count(DISTINCT threads.thread_id) AS thread_count
			FROM users
			LEFT JOIN comments ON comments.user_id = users.id
			LEFT JOIN threads ON threads.user_id = users.id
			WHERE LOWER(username) = ?";
		
		return $this->db->query($sql, $username);
		
	}
	/**
	 * Pretty self-explanatory get user recent posts. also concactenate a link in sql
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_recent_posts($user_id, $start=0, $limit)
	{
		$sql = "
			SELECT
				threads.subject, 
				threads.thread_id, 
				comments.comment_id, 
				comments.thread_id, 
				comments.user_id, 
				comments.created,
				comments.deleted,
				comments.content,
				concat('/thread/', threads.thread_id, '/', threads.subject) as thread_rel_url
			FROM comments 
			LEFT JOIN threads
				ON comments.thread_id = threads.thread_id
			WHERE comments.user_id = ?
				AND deleted != 0
			ORDER BY comment_id DESC
			LIMIT ?, ?";
		
		$query = $this->db->query($sql, array(
			$user_id,
			$start, 
			$limit
		));
		
		if ($query->num_rows() > 0)
			return $query->result_array();
		
		return NULL;
	}
	/**
	 * Pretty self-explanatory
	 *
	 * @return	object
	 */
	function get_active_users($user_id)
	{
		$sql = "
			SELECT 
				DISTINCT users.username
			FROM users 
			RIGHT JOIN acquaintances 
			ON acquaintances.acq_user_id = users.id 
			LEFT JOIN sessions
			ON sessions.user_id = users.id
			WHERE acquaintances.user_id = ?
			AND sessions.user_id != 0 
			AND sessions.last_activity > (UNIX_TIMESTAMP() - 300)			
			AND acquaintances.type = 1";
			
		$data['buddies'] = $this->db->query($sql, $user_id);
		
		$sql = "
			SELECT count(users.id) AS buddy_count
			FROM users
			RIGHT JOIN acquaintances
			ON acquaintances.acq_user_id = users.id
			WHERE acquaintances.user_id = ?
			AND acquaintances.type = 1";
			
		$data['buddy_count'] = $this->db->query($sql, $user_id)->row()->buddy_count;
		
		return $data;
	}
	
	function add_acquaintance($key, $user_id, $acq_id, $type)
	{
		$this->db->query("INSERT INTO acquaintances (acq_id, user_id, acq_user_id, type) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE type = ?", array(
			$key,
			$user_id,
			$acq_id,
			$type,
			$type
		));
		
		return $this->db->affected_rows() === 1;
	}
	
	function acquaintance_exists($user_id, $acq_id)
	{
		$result = $this->db->query("SELECT user_id FROM acquaintances WHERE user_id = ? AND acq_user_id = ?", array(
			$user_id,
			$acq_id
		));
		
		return $result->num_rows > 0;
	}
	
	function delete_acquaintance($key)
	{
		$this->db->query("DELETE FROM acquaintances WHERE key = ?", $key);
	}
	
	function get_buddies($user_id)
	{
		$result = $this->db->query("
			SELECT 
				DISTINCT users.username,
				IFNULL(sessions.user_id, 0) AS online
			FROM users 
			RIGHT JOIN acquaintances 
			ON acquaintances.acq_user_id = users.id 
			LEFT JOIN sessions
			ON sessions.user_id = users.id
			WHERE acquaintances.user_id = ? 
			AND acquaintances.type = 1
			AND sessions.last_activity > (UNIX_TIMESTAMP() - 300)", $user_id);
		
		return $result->num_rows > 0 ? $result : FALSE;
	}
	
	function get_enemies($user_id)
	{
		$result = $this->db->query("
			SELECT 
				DISTINCT users.username,
				IFNULL(sessions.user_id, 0) AS online
			FROM users 
			RIGHT JOIN acquaintances 
			ON acquaintances.acq_user_id = users.id 
			LEFT JOIN sessions
			ON sessions.user_id = users.id
			WHERE acquaintances.user_id = ? 
			AND acquaintances.type = 2
			AND sessions.last_activity > (UNIX_TIMESTAMP() - 300)", $user_id);
		
		return $result->num_rows > 0 ? $result : FALSE;
	}
}