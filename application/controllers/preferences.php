<?php

class Preferences extends Controller {

	function Preferences()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('auth/users');
		
		if (!$this->sauth->is_logged_in())
			redirect('/');
	}
	
	/**
	 * Show/save preferences
	 *
	 * @return void
	 */
	function index()
	{
		if (!$this->sauth->is_logged_in())
		{
			redirect('');
		}
		else
		{
			$this->form_validation->set_rules('threads_shown', 'Threads Shown', 'trim|is_natural|required|xss_clean');
			$this->form_validation->set_rules('comments_shown', 'Comments Shown', 'trim|is_natural|required|xss_clean');
			
			$data['errors'] = array();
			
			if ($this->form_validation->run())
			{
				$data = array(
					'threads_shown' => $this->form_validation->set_value('threads_shown'),
					'comments_shown' => $this->form_validation->set_value('comments_shown'),
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
}

/* End of file preferences.php */
/* Location: ./application/controllers/preferences.php */