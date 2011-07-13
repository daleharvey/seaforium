<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_user extends Controller
{
	function Ajax_user()
	{
		parent::__construct();
		
		$this->load->helper(array('url', 'content_render'));
		$this->load->library(array('form_validation', 'sauth', 'email'));
		$this->load->model(array('user_dal'));
	}
	
	function forgot_password()
	{
		// set validation for the form
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('key', 'Key', 'required');
		
		// if the form was actually submitted
		if ($this->form_validation->run())
		{
			// get the values
			$email = $this->form_validation->set_value('email');
			$key = $this->form_validation->set_value('key');
			
			// make sure the session key matches
			if ($key === $this->session->userdata('session_id'))
			{
				// find the user
				$user = $this->user_dal->get_user_by_email($email);
				
				// if user exists
				if ($user)
				{
					$passwords = array(
						'airplane',
						'apple',
						'booger',
						'bug',
						'burrito',
						'catapult',
						'dude',
						'godzilla',
						'hamburger',
						'jabba',
						'jacket',
						'peach',
						'red',
						'silly',
						'stupid',
						'sunshine',
						'taco',
						'threadless',
						'wookie',
						'yes'
					);
					
					// make some data to throw at auth
					$data = array(
						'id' => $user->id,
						'password' => $passwords[mt_rand(0, 19)] . mt_rand(10, 99) . $passwords[mt_rand(0, 19)]
					);
					
					// reset it!
					$this->sauth->reset_password($data);
					
					$this->email->from('castis@gmail.com', 'YayHooray.net');
					$this->email->to($email);
					$this->email->subject('Your new password!');
					$this->email->message($this->load->view('emails/forgot_password', $data, true));
					
					$this->email->send();
					
					$this->load->view('forgot_password/success', array('email' => $email));
				}
				else
				{
					$this->load->view('forgot_password/request', array('error' => "Hmm, I couldn't find any accounts with that email address. Are you sure that's the one you signed up with?"));
				}
			}
			
			// YOU GET NOTHING, SIR!
		}
		else
		{
			$this->load->view('forgot_password/request', array('error' => ''));
		}
	}
}