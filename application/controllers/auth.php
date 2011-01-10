<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'string'));
		$this->load->library(array('form_validation', 'sauth', 'yayhooray'));
		$this->load->model('user_dal');
	}

	function index()
	{
		redirect('/auth/login');
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{
		if ($this->sauth->is_logged_in())
		{
			redirect('');
		}
		else
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
			
			$data['errors'] = array();
			
			if ($this->form_validation->run()) {
				if ($this->sauth->login(
					$this->form_validation->set_value('username'),
					$this->form_validation->set_value('password')))
				{
					redirect('');
				}
				else
				{
					$data['errors'] = $this->sauth->get_error_message();
				}
			}
			
			$this->load->view('shared/secluded_header');
			$this->load->view('auth/login', $data);
			$this->load->view('shared/secluded_footer');
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->sauth->logout();

		redirect('/');
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register($key = '')
	{
		if ($this->sauth->is_logged_in())
		{
			redirect('');
		}
		else
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length[2]|max_length[18]|alpha_dash');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('key', 'Key', 'trim|required|xss_clean');
			
			$data['errors'] = array();
			
			if ($this->form_validation->run())
			{
				if ($this->sauth->create_user(
						$this->form_validation->set_value('username'),
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('key')))
				{
					
					$this->sauth->login($this->form_validation->set_value('username'), $this->form_validation->set_value('password'));
					
					redirect('');
				}
				else
				{
					$data['errors'] = $this->sauth->get_error_message();
				}
			}
			
			$data['key'] = $key;
			
			$this->load->view('shared/secluded_header');
			$this->load->view('auth/register', $data);
			$this->load->view('shared/secluded_footer');
		}
	}
	
	/**
	 * Invites a user based on their YH username
	 *
	 * @return void
	 */
	function invite()
	{
		if ($this->sauth->is_logged_in())
		{
			redirect('');
		}
		else
		{
			$this->form_validation->set_rules('yhuser', 'Username', 'trim|required|xss_clean|min_length[2]|max_length[18]');
			
			$data['errors'] = array();
			
			if ($this->form_validation->run())
			{
				$invite_id = random_string('alnum', 32);
				
				if ($this->sauth->yh_invite(
					$this->form_validation->set_value('yhuser'),
					$invite_id))
				{
						$message = <<<EOT
Hey {$this->form_validation->set_value('yhuser')},

Heres a link for you to register at the new board we've got!

http://sparklebacon.net/auth/register/{$invite_id}

castis
EOT;
					
					//exit($message);
					
					$this->yayhooray->login('castis', '');
					$this->yayhooray->send_message($this->form_validation->set_value('yhuser'), 'Your invite to the new board', $message);
					
					$data['errors'] = $this->sauth->get_confirmation_message();
				}
				else
				{
					$data['errors'] = $this->sauth->get_error_message();
				}
			}
			
			$this->load->view('shared/secluded_header');
			$this->load->view('auth/login', $data);
			$this->load->view('shared/secluded_footer');
		}
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */