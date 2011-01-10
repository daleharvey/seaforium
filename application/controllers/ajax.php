<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller
{
	function Ajax()
	{
		parent::__construct();
		
		$this->load->model('thread_dal');
	}
	
	function thread_notifier($current_count)
	{
		
	}
	
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */