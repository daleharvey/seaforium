<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		
		$this->load->helper(array('date', 'url'));
		$this->load->library('pagination');
		$this->load->model('thread_dal');
	}
	
	function index($pagination = 0, $filter = '')
	{
		$user_id = (int) $this->session->userdata('user_id');
		
		switch($filter)
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
			case 'meaningful':
				$sql = "WHERE threads.category != 4";
				break;
			case 'participated':
				$sql = "WHERE threads.thread_id IN (". $this->thread_dal->get_participated_threads($user_id) .")";
				break;
			default:
				$filter = $sql = '';
		}
		
		$display = $this->session->userdata('threads_shown') == false ? 50 : $this->session->userdata('threads_shown');
		
		$this->pagination->initialize(array(
			'base_url' => '/p/',
			'total_rows' => $this->thread_dal->get_comment_count($sql),
			'uri_segment' => '2',
			'per_page' => $display,
			'suffix' => strlen($filter) > 0 ? '/'.$filter : ''
		)); 
		
		$this->load->view('shared/header');
		
		$this->load->view('threads', array(
			'title' => $this->thread_dal->get_front_title(),
			'thread_result' => $this->thread_dal->get_threads($pagination, $display, $sql),
			'pagination' => $this->pagination->create_links()
		));
		
		$this->load->view('shared/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */