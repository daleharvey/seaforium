<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		
		$this->load->helper(array('date', 'url'));
		$this->load->library('pagination');
		$this->load->model('thread_dal');
	}
	
	function index($pagination = 0)
	{
		$display = $this->session->userdata('threads_shown') == false ? 50 : $this->session->userdata('threads_shown');
		
		$this->pagination->initialize(array(
			'base_url' => '/p/',
			'total_rows' => $this->thread_dal->get_comment_count(),
			'uri_segment' => '2',
			'per_page' => $display
		)); 
		
		$this->load->view('shared/header');
		
		$this->load->view('threads', array(
			'title' => $this->thread_dal->get_front_title(),
			'thread_result' => $this->thread_dal->get_threads($pagination, $display),
			'pagination' => $this->pagination->create_links()
		));
		
		$this->load->view('shared/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */