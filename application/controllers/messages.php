<?php

class Messages extends Controller {

	function Messages()
	{
		parent::Controller();

		$this->load->helper(array('form', 'url', 'content_render'));
		$this->load->library('form_validation');

		$this->load->model(array('message_dal', 'user_dal'));

		if (!$this->sauth->is_logged_in())
			redirect('/');
    
    $this->meta = array('user_id' => $this->session->userdata('user_id'));
	}

	function index()
	{
		redirect('/messages/inbox');
	}

	function inbox($pagination = 0)
	{
		$data = array();

		$data['messages'] = $this->message_dal->get_inbox($this->meta['user_id']);

		$this->load->view('shared/header');
		$this->load->view('messages/inbox', $data);
		$this->load->view('shared/footer');
	}

	function outbox($pagination = 0)
	{

		$data = array();

		$data['messages'] = $this->message_dal->get_outbox($this->meta['user_id']);

		$this->load->view('shared/header');
		$this->load->view('messages/outbox', $data);
		$this->load->view('shared/footer');
	}
	
	function action($box = 'inbox')
	{
		$this->form_validation->set_rules('action', 'action', 'trim|required|xss_clean');
		$this->form_validation->set_rules('message_ids[]', 'message_ids', 'required|integer');
		
		if ($this->form_validation->run()) {
			
			$action = $this->form_validation->set_value('action');
			$message_ids = $this->form_validation->set_value('message_ids[]');
			
			switch($action)
			{
				case "unread":
					$this->message_dal->set_unread_in_array($this->meta['user_id'], $message_ids);
					break;
				case "read":
					$this->message_dal->set_read_in_array($this->meta['user_id'], $message_ids);
					break;
				case "indelete":
					$this->message_dal->delete_in_array_inbox($this->meta['user_id'], $message_ids);
					break;
				case "outdelete":
					$this->message_dal->delete_in_array_outbox($this->meta['user_id'], $message_ids);
					break;
				default:
			}
			
		}
		
		redirect("/messages/". ($box == 'outbox' ? 'outbox' : 'inbox'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */