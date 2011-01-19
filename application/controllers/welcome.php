<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		
		$this->load->helper(array('date', 'url'));
		$this->load->library('pagination');
		$this->load->model('thread_dal');
	}
	
	function index($pagination = 0, $filter = '', $ordering = '', $order_dir = 'desc')
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
			case 'all':
			default:
				$filter = $sql = '';
		}
		
		$order_dir = strtolower($order_dir);
		
		if ($order_dir != 'desc' && $order_dir != 'asc')
			$order_dir = 'desc';
		
		switch($ordering)
		{
			case 'started':
				$sql_dir = "ORDER BY threads.created ". $order_dir;
				break;
			case 'latest':
				$sql_dir = "ORDER BY response_created ". $order_dir;
				break;
			case 'posts':
				$sql_dir = "ORDER BY response_count ". $order_dir;
				break;
			default:
				$sql_dir = "ORDER BY response_created DESC";
				$ordering = $order_dir = '';
		}
		
		$display = $this->session->userdata('threads_shown') == false ? 50 : $this->session->userdata('threads_shown');
		
		$paging_suffix = strlen($filter) > 0 ? '/'.$filter : '';
		$paging_suffix .= strlen($ordering) > 0 ? '/'.$ordering : '';
		$paging_suffix .= strlen($order_dir) > 0 ? '/'.$order_dir : '';
		
		$this->pagination->initialize(array(
			'base_url' => '/p/',
			'total_rows' => $this->thread_dal->get_comment_count($sql),
			'uri_segment' => '2',
			'per_page' => $display,
			'suffix' => $paging_suffix
		)); 
		
		$this->load->view('shared/header');
		
		$this->load->view('threads', array(
			'title' => $this->thread_dal->get_front_title(),
			'thread_result' => $this->thread_dal->get_threads($user_id, $pagination, $display, $sql, $sql_dir),
			'pagination' => $this->pagination->create_links(),
			'tab_links' => strlen($filter) > 0 ? '/f/'.$filter.'/' : '/o/',
			'tab_orders' => array(
				'started' => $ordering == 'started' && $order_dir == 'desc' ? 'asc' : 'desc',
				'latest' => $ordering == 'latest' && $order_dir == 'desc' ? 'asc' : 'desc',
				'posts' => $ordering == 'posts' && $order_dir == 'desc' ? 'asc' : 'desc'
			)
		));
		
		$this->load->view('shared/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */