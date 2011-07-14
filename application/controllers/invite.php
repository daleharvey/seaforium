<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invite extends Controller
{
	function Invite()
	{
		parent::__construct();
		
		$this->load->helper(array('url'));
		$this->load->library(array('form_validation', 'yayhooray'));
		$this->load->model('thread_dal');
		
		if ($this->sauth->is_logged_in())
			redirect('/');
	}

	function index() {
		
		$this->form_validation->set_rules('yh_username', 'YayHooray username', 'trim|required|xss_clean|min_length[2]|max_length[18]');
		
		if ($this->form_validation->run())
		{
			$username = $this->form_validation->set_value('yh_username');
			$invite_id = random_string('alnum', 32);
			
			if ($this->sauth->yh_invite(
					$username,
					$invite_id))
				{
					$message = $this->load->view('emails/yh_invite', array('username' => $username, 'invite_id' => $invite_id), true);
					
					$this->yayhooray->login('username', 'password');
					
					$this->yayhooray->send_message($username, 'Your invite to the new board', $message);
					
					$this->load->view('shared/header');
					$this->load->view('invite/confirmation');
					$this->load->view('shared/footer');
			}
			else
			{
				$this->load->view('shared/header');
				$this->load->view('invite/invite', array('error', 'Looks like that username has already been sent an invite!'));
				$this->load->view('shared/footer');
			}
		}
		else
		{
			$this->load->view('shared/header');
			$this->load->view('invite/invite');
			$this->load->view('shared/footer');
		}
	}
	
}

/* End of file invite.php */
/* Location: ./application/controllers/invite.php */