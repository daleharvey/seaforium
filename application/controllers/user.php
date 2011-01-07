<?php

class User extends Controller {

	function User()
	{
		parent::Controller();

		$this->load->helper(array('url', 'number'));
	}
	
	function index()
	{
		redirect('/');
	}
	
	function load($username)
	{
		$username = str_replace('-', ' ', $username);
		
		$query = $this->db->query('
			SELECT 
				users.id, 
				users.username, 
				users.created, 
				users.last_login,
				count(DISTINCT comments.comment_id) AS comment_count,
				count(DISTINCT threads.thread_id) AS thread_count
			FROM users
			LEFT JOIN comments ON comments.user_id = users.id
			LEFT JOIN threads ON threads.user_id = users.id
			WHERE LOWER(username) = '. strtolower($this->db->escape($username)) .'
		');
		
		if ($query->result_id->num_rows === 0)
			redirect('/');
		
		$data['user_data'] = $query->row();
		$data['user_data']->average_posts = number_format(($data['user_data']->thread_count + $data['user_data']->comment_count) / (floor((time() - strtotime($data['user_data']->created))) / 86400), 2);
		
		$data['user_data']->last_login_text = (strtotime($data['user_data']->last_login) == null)
			? " hasn't logged in yet."
			: ' last logged in on '. date('F jS Y \a\t g:i a', strtotime($data['user_data']->last_login)) .'.';
		
		$this->load->view('shared/header');
		$this->load->view('user', $data);
		$this->load->view('shared/footer');
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */