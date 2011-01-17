<?php

class Message extends Controller {

	function Message()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		//$this->load->library('form_validation');
		
		$this->load->model('message_dal');
	}
	
	function index(){}
	
	function load($message_id)
	{
		
		$data = array();
		
		$user_id = (int)$this->session->userdata('user_id');
		
		if (!$data['message'] = $this->message_dal->get_message($user_id, $message_id)->row())
		{
			$data['errors'] = "Either that message does not exist or you do not have rights to view it";
		}
		
		$this->load->view('shared/header');
		$this->load->view('messages/message', $data);
		$this->load->view('shared/footer');
	}
	
	
}

/* End of file message.php */
/* Location: ./application/controllers/message.php */