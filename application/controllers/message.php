<?php

class Message extends Controller {

	function Message()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url', 'content_render'));
		$this->load->library('form_validation');
		
		$this->load->model('message_dal');
		
		if (!$this->sauth->is_logged_in())
			redirect('/');
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
      show_404('/message/' . $message_id);
			//redirect('/');
		}
	}
  
  function send($to = '')
  {
    $user_id = (int)$this->session->userdata('user_id');

		$data = array(
      'to' => str_replace('-', ' ', $to),
      
      // this is only used by /reply but we're using the same view to make things easier
      'message' => array('recipients' => '', 'subject' => '', 'content' => '')
      );

		$this->form_validation->set_message('required', "%s is required");
		$this->form_validation->set_error_delimiters('<li>', '</li>');

		$this->form_validation->set_rules('recipients', 'At least one recipient', 'trim|required|xss_clean');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('save_sent', 'Save sent');
		$this->form_validation->set_rules('read_receipt', 'Read receipt');
		$this->form_validation->set_rules('content', 'Content', 'trim|required|xss_clean');

    // process the error checking on the form
		if ($this->form_validation->run())
		{
			// array of user names
			$usernames = explode(',', $this->form_validation->set_value('recipients'));

      // TODO: remember why I limited it to 10.
			if (count($usernames) < 11)
			{
        
        
				// translated into user ids
				$user_ids = $this->user_dal->get_user_ids_from_array($this->session->userdata('user_id'), $usernames);

        // make sure the amount of users returned by the database
        // matches how many users we want to message
				if (count($usernames) === $user_ids->num_rows)
				{
					$recipient_ids = array();
					$have_me_enemied = array();

					// loop through the results to pull out user ids and see if anyone has me enemied
					foreach($user_ids->result() as $row)
					{
						$recipient_ids[] = (int)$row->id;
						if ($row->type == '2') // this user has me as their enemy
							$have_me_enemied[] = $row->username;
					}

					// if no one has me enemied
					if (count($have_me_enemied) === 0)
					{
						// put together the data for a new pm
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
							$this->message_dal->new_inbox($recipient, $message, $this->form_validation->set_value('read_receipt'));

							// if we want to save a message to our outbox
							if ($this->form_validation->set_value('save_sent') == 'save')
							{
								$this->message_dal->new_outbox($recipient, $message);
							}
						}

						// redirect them to the inbox
            redirect('/messages/inbox');
					}
					// if there was 1 recipient and that 1 person has me enemied
					elseif (count($have_me_enemied) === 1 && count($recipient_ids) === 1)
					{
						$data['errors'] = "<li>That user has you enemied.</li>";
					}
					// if there were multiple recipients and 1 or more of them have me enemied
					else
					{
						$data['errors'] = "<li>The following users have you enemied:<ul>";
						foreach($have_me_enemied as $jerk)
						{
							$data['errors'] .= '<li>'. $jerk .'</li>';
						}
						$data['errors'] .= '</ul></li>';
					}
				}
        // the amount of usernames returned by the database does not
        // match the amount of users we put in the 'to' field
				else
				{
					$data['errors'] = "<li>One or more of the recipients does not exist</li>";
				}
			}
			else
			{
				$data['errors'] = validation_errors();
			}
		}
    
    //$data['buddies'] = $this->user_dal->get_buddies($user_id)->result();
    
		$this->load->view('shared/header');
		$this->load->view('messages/send', $data);
		$this->load->view('shared/footer');
  }
  
  function reply($message_id = 0)
  {
		$user_id = (int)$this->session->userdata('user_id');
    
		if ($message = $this->message_dal->get_message($user_id, $message_id))
		{
    
			$message = $message->row();

      $data['message'] = array(
        'recipients' => $message->username,
        'subject' => 'RE: '. $message->subject,
        'content' => "\n\n\n-----------------------------\n\n". $message->content
      );
		}
		else
		{
			show_404('/message/reply/' . $message_id);
		}

		$this->load->view('shared/header');
		$this->load->view('messages/send', $data);
		$this->load->view('shared/footer');
  }
}

/* End of file message.php */
/* Location: ./application/controllers/message.php */