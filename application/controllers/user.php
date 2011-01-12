<?php

class User extends Controller {

	function User()
	{
		parent::Controller();

		$this->load->helper(array('url', 'number'));
		$this->load->model('user_dal');
	}
	
	function index()
	{
		redirect('/');
	}
	
	function load($username)
	{
		$query = $this->user_dal->get_profile_information(str_replace('-', ' ', $username));
		
		
		if ($query->result_id->num_rows === 0)
			redirect('/');
		
		$data['user_data'] = $query->row();
		
		$data['user_data']->average_posts = number_format(
			$data['user_data']->thread_count + $data['user_data']->comment_count / // total posts, divided by
			(ceil((time() - strtotime($data['user_data']->created)) / 86400)) // days
			, 2);
		
		$data['user_data']->last_login_text = (strtotime($data['user_data']->last_login) == null)
			? " hasn't logged in yet."
			: ' last logged in on '. date('F jS Y \a\t g:i a', strtotime($data['user_data']->last_login)) .'.';
			
			$data['recent_posts'] = $this->user_dal->get_user_recent_posts($data['user_data']->id);
		
		$this->load->view('shared/header');
		$this->load->view('user', $data);
		$this->load->view('shared/footer');
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */