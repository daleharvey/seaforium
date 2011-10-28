<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Status extends Controller
{
	function Status()
	{
		parent::__construct();
		
		$this->load->helper(array('url'));
		$this->load->library(array('form_validation', 'yayhooray'));
		$this->load->model('thread_dal');
	}

	function index() {
		
		echo "<pre>";
		
		var_dump($this->session);
		
		echo "</pre>";
		
	}
	
}

/* End of file invite.php */
/* Location: ./application/controllers/invite.php */