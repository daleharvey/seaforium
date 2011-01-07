<?php

class Preferences extends Controller {

	function Preferences()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		if (!$this->sauth->is_logged_in())
			redirect('/');
	}
	
	function index()
	{
		
		$this->form_validation->set_rules('threads_shown', 'Threads Shown', 'trim|integer|required|xss_clean');
		$this->form_validation->set_rules('comments_shown', 'Comments Shown', 'trim|integer|required|xss_clean');
		
		if ($this->form_validation->run())
		{
			$threads_shown = $this->form_validation->set_value('threads_shown');
			$comments_shown = $this->form_validation->set_value('comments_shown');
			
			$data = array(
				'threads_shown' => $threads_shown,
				'comments_shown' => $comments_shown,
			);
			
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('users', $data);
			
			$this->session->set_userdata($data);
		}
		
		$data = array();
		
		$this->load->view('shared/header');
		$this->load->view('preferences', $data);
		$this->load->view('shared/footer');
	}
}

/* End of file preferences.php */
/* Location: ./application/controllers/preferences.php */