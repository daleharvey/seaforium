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
	}

	function index()
	{
		redirect('/messages/inbox');
	}

	function send($to = '')
	{
    $user_id = (int)$this->session->userdata('user_id');

		$data = array('to' => str_replace('-', ' ', $to));

		$this->form_validation->set_message('required', "%s is required");
		$this->form_validation->set_error_delimiters('<li>', '</li>');

		$this->form_validation->set_rules('recipients', 'At least one recipient', 'trim|required|xss_clean');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('save_sent', 'Save sent');
		$this->form_validation->set_rules('read_receipt', 'Read receipt');
		$this->form_validation->set_rules('content', 'Content', 'trim|required|xss_clean');

		if ($this->form_validation->run())
		{
			// array of user names
			$usernames = explode(',', $this->form_validation->set_value('recipients'));

			if (count($usernames) < 11)
			{

				// translated into user ids
				$user_ids = $this->user_dal->get_user_ids_from_array($this->session->userdata('user_id'), $usernames);

				if (count($usernames) === $user_ids->num_rows)
				{
					$recipient_ids = array();
					$have_me_enemied = array();

					// loop through the results to pull out user ids and see if anyone has me enemied
					foreach($user_ids->result() as $row)
					{
						$recipient_ids[] = (int)$row->id;
						if ($row->type == '2')
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
					// if there were multiple recipients and most of them have me enemied
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
    
    $data['buddies'] = $this->user_dal->get_buddies($user_id)->result();
    
		$this->load->view('shared/header');
		$this->load->view('messages/send', $data);
		$this->load->view('shared/footer');
	}

	function reply($message_id)
	{
		$user_id = (int)$this->session->userdata('user_id');

		if ($message = $this->message_dal->get_message($user_id, $message_id))
		{
			$data['message'] = $message->row();

			$data['message']->subject = 'RE: '. $data['message']->subject;

			$data['message']->content = "\n \n \n-----------------------------\n \n". $data['message']->content;
		}
		else
		{
			$null = new stdClass;
			$null->username = '';
			$null->subject = '';
			$null->content = '';

			$data['message'] = $null;
			$data['errors'] = "Either that message does not exist or you do not have rights to view it";
		}

		$this->load->view('shared/header');
		$this->load->view('messages/reply', $data);
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
	
	function action()
	{
		$this->form_validation->set_rules('action', 'action', 'trim|required|xss_clean');
		$this->form_validation->set_rules('message_ids[]', 'message_ids', 'required|integer');
		
		if ($this->form_validation->run()) {
			
			$action = $this->form_validation->set_value('action');
			$message_ids = $this->form_validation->set_value('message_ids[]');
			
			switch($action)
			{
				case "unread":
					$this->message_dal->set_unread_in_array($this->session->userdata('user_id'), $message_ids);
					break;
				case "read":
					$this->message_dal->set_read_in_array($this->session->userdata('user_id'), $message_ids);
					break;
				case "delete":
					$this->message_dal->delete_in_array($this->session->userdata('user_id'), $message_ids);
					break;
				default:
			}
			
		}
		
		redirect("/messages/inbox");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */