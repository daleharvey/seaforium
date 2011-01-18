<?php

class Message extends Controller {

	function Message()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url', 'content_render'));
		//$this->load->library('form_validation');
		
		$this->load->model('message_dal');
	}
	
	function index(){}
	
	function load($message_id)
	{
		
		$data = array();
		
		$user_id = (int)$this->session->userdata('user_id');
		
		$message = $this->message_dal->get_message($user_id, $message_id);
		
		if ($message != FALSE)
		{
			$data['message'] = $message->row();
		}
		else
		{
			redirect('/');
		}
		
		if ((int)$data['message']->to_id == $user_id && $data['message']->read_receipt == '1')
		{
			$message = array(
				'sender' => $user_id,
				'recipient' => $data['message']->sender_id,
				'subject' => 'Receipt for: '. $data['message']->subject,
				'content' => 'Your message has been read.'
			);
			
			$message['id'] = $this->message_dal->new_message($message);
			
			$this->message_dal->new_inbox($message['recipient'], $message, '');
			$this->message_dal->new_outbox($message['recipient'], $message);
			$this->message_dal->read_receipt_sent($message_id);
		}
		
		$this->message_dal->set_read($user_id, $message_id);
		
		$this->load->view('shared/header');
		$this->load->view('messages/message', $data);
		$this->load->view('shared/footer');
	}
	
	
}

/* End of file message.php */
/* Location: ./application/controllers/message.php */