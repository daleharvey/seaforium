<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Autologin extends Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get user data for auto-logged in user.
	 * Return NULL if given key or user ID is invalid.
	 *
	 * @param	int
	 * @param	string
	 * @return	object
	 */
	function get($user_id, $key)
	{
		$this->db->select('users.id');
		$this->db->select('users.username');
		$this->db->from('users');
		$this->db->join('user_autologin', 'user_autologin.user_id = users.id');
		$this->db->where('user_autologin.user_id', $user_id);
		$this->db->where('user_autologin.key_id', $key);
		$query = $this->db->get();
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	
	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set($user_id, $key)
	{
		return $this->db->insert('user_autologin', array(
			'user_id' 		=> $user_id,
			'key_id'	 	=> $key,
			'user_agent' 	=> substr($this->input->user_agent(), 0, 149),
			'last_ip' 		=> $this->input->ip_address(),
		));
	}
	
	/**
	 * Delete user's autologin data
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function delete($user_id, $key)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('key_id', $key);
		$this->db->delete('user_autologin');
	}
	
	/**
	 * Delete all autologin data for given user
	 *
	 * @param	int
	 * @return	void
	 */
	function clear($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_autologin');
	}
	
	/**
	 * Purge autologin data for given user and login conditions
	 *
	 * @param	int
	 * @return	void
	 */
	function purge($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('user_agent', substr($this->input->user_agent(), 0, 149));
		$this->db->where('last_ip', $this->input->ip_address());
		$this->db->delete('user_autologin');
	}
	
}