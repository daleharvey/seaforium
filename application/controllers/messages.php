<?php

class Messages extends Controller {

	function Messages()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->load->model(array('message_dal', 'user_dal'));
	}
	
	function index()
	{
		redirect('/messages/inbox');
	}
	
	function send()
	{
		
		$data = array();
		
		$this->form_validation->set_rules('recipients', 'Recipients', 'trim|required|xss_clean');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('content', 'Content', 'trim|required|xss_clean');
		
		if ($this->form_validation->run())
		{
			// array of user names
			$usernames = explode(',', $this->form_validation->set_value('recipients'));
			
			// translated into user ids
			$user_ids = $this->user_dal->get_user_ids_from_array($usernames);
			
			if (count($usernames) === $user_ids->num_rows)
			{
				
				$recipient_ids = array();
				
				foreach($user_ids->result() as $row)
				{
					$recipient_ids[] = (int)$row->id;
				}
				
				$message = array(
					'sender' => (int)$this->session->userdata('user_id'),
					'recipients' => $recipient_ids,
					'subject' => $this->form_validation->set_value('subject'),
					'content' => $this->form_validation->set_value('content')
				);
				
				// insert a new PM into the database
				$message['id'] = $this->message_dal->new_message($message);
				
				// loop through all recipients
				foreach($message['recipients'] as $recipient)
				{
					// send the message and increment the message counter
					$this->message_dal->new_inbox($recipient, $message);
					$this->message_dal->new_outbox($recipient, $message);
				}
				
			}
			else
			{
				$data['errors'] = "One or more of the recipients does not exist";
			}
		}
		
		$this->load->view('shared/header');
		$this->load->view('messages/send', $data);
		$this->load->view('shared/footer');
	}
	
	function inbox($pagination = 0)
	{
		
		$data = array();
		
		$data['messages'] = $this->message_dal->get_inbox($this->session->userdata('user_id'));
		
		$this->load->view('shared/header');
		$this->load->view('messages/inbox', $data);
		$this->load->view('shared/footer');
	}
	
	function outbox($pagination = 0)
	{
		
		$data = array();
		
		$data['messages'] = $this->message_dal->get_outbox($this->session->userdata('user_id'));
		
		$this->load->view('shared/header');
		$this->load->view('messages/outbox', $data);
		$this->load->view('shared/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */