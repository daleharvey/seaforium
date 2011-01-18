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
		$user_id = (int)$this->session->userdata('user_id');
		
		$message = $this->message_dal->get_message($user_id, $message_id);
		
		// data is only returned if the user requesting is the recipient or the sender
		if ($message != FALSE)
		{
			$message = $message->row();
			
			// if the recipient is reading the message
			if ((int)$message->to_id == $user_id)
			{
				// if a read receipt was requested
				if ($message->read_receipt == '1' && $message->read == '0')
				{
					$receipt = array(
						'sender' => $user_id, // reader of the original message
						'recipient' => $message->sender_id, // user who sent the original message
						'subject' => 'Receipt for: '. $message->subject,
						'content' => 'Your message has been read.'
					);
					
					// new message in the database
					$receipt['id'] = $this->message_dal->new_message(array(
						'subject' => $message->subject,
						'content' => $message->content
					));
					
					// send the receipt to the inbox of the original sender
					$this->message_dal->new_inbox($receipt['recipient'], $receipt, '');
					
					// and put an outbox notification in the outbox of the original recipient
					$this->message_dal->new_outbox($receipt['recipient'], $receipt);
				}
				
				// if this message is unread, change that
				if ($message->read == '0')
					$this->message_dal->set_read($user_id, $message_id);
				
			}
			
			$this->load->view('shared/header');
			$this->load->view('messages/message', array('message' => $message));
			$this->load->view('shared/footer');
		}
		else
		{
			redirect('/');
		}
	}
}

/* End of file message.php */
/* Location: ./application/controllers/message.php */