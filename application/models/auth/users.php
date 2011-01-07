<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Model
{
	function Users()
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
		$this->db->where('id', $user_id);
		
		$query = $this->db->get('users');
		
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
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get('users');
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	
	/**
	 * Get yh username by invite id
	 *
	 * @param	string
	 * @return	object
	 */
	function get_yh_username_by_invite($invite_id)
	{
		$this->db->where('invite_id', $invite_id);

		$query = $this->db->get('yh_invites');
		
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
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get('users');
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
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->get('users');
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
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(yh_username)=', strtolower($username));
		
		$query = $this->db->get('yh_invites');
		
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
		$this->db->select('1', FALSE);
		$this->db->where('invite_id', $key);
		$this->db->where('used', '0');
		
		$query = $this->db->get('yh_invites');
		
		return $query->num_rows() == 0;
	}
	
	/**
	 * Create new invite for yh user
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_yh_invite($username, $invite_id)
	{
		$data['invite_id'] = $invite_id;
		$data['yh_username'] = $username;
		$data['created'] = date('Y-m-d H:i:s');
		
		if ($this->db->insert('yh_invites', $data))
		{
			return array('confirmation' => '');
		}
	}
	
	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $invite_id)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = 1;

		if ($this->db->insert('users', $data))
		{
			$this->set_yh_invite_used($invite_id);
			
			$user_id = $this->db->insert_id();
			$this->create_profile($user_id);
			return $user_id;
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
		$this->db->where('invite_id', $invite_id);
		$this->db->update('yh_invites', array('used' => '1'));
	}
	
	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert('user_profiles');
	}
	
	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete('users');
	}
	
	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete('users');
		if ($this->db->affected_rows() > 0)
		{
			$this->delete_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id)
	{
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);

		$this->db->set('last_ip', $this->input->ip_address());
		$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update('users');
	}
	
}