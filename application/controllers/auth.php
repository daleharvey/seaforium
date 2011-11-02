<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->helper(array('form', 'url', 'string', 'utils'));
    $this->load->library(array('form_validation', 'sauth', 'yayhooray', 'email', 'recaptcha'));
    $this->load->model('user_dal');
  }

  /**
   * Login user on the site
   *
   * @return void
   */
  function login()
  {
    $this->form_validation->set_rules('username', 'Username', 'trim|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');

    if (!$this->form_validation->run()) {
      return send_json($this->output, 412, array('error' => 'invalid login details'));
    }

    $username = $this->form_validation->set_value('username');
    $password = $this->form_validation->set_value('password');

    if (!$this->sauth->login($username, $password)) {
      $json = array('error' => $this->sauth->error['msg']);
      return send_json($this->output, 401, $json);
    }

    return send_json($this->output, 200, array('ok' => true));
  }

  function activate($key)
  {
    $username = $this->user_dal->get_username_from_authkey($key);

    if (!$username || $this->user_dal->is_yh_invite_used($key)) {
      $this->output->set_status_header(401);
      $this->load->view('shared/header');
      $this->load->view('notice', array('header' => 'Activation Error',
                                        'msg' => "Invalid key"));
      $this->load->view('shared/footer');
      return;
    }

    $this->user_dal->activate_user($username);
    $this->user_dal->set_yh_invite_used($key);

    $this->load->view('shared/header');
    $this->load->view('notice', array('header' => 'Activation Successful',
                                      'msg' => "You will now be able to login"));
    $this->load->view('shared/footer');
  }

  /**
   * Logout user
   *
   * @return void
   */
  function logout()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->sauth->logout();
      return redirect('/');
    }
  }
  
  /**
   * Register user on the site
   *
   * @return void
   */
  function register()
  {
    $data = array(
      'recaptcha' => $this->recaptcha->get_html()
    );
    
    $view = "auth/register";
    
    $this->form_validation->set_rules('username', 'Username',
                                      'trim|required|xss_clean|callback_valid_username');
    $this->form_validation->set_rules('email', 'Email',
                                      'trim|required|xss_clean|valid_email|callback_valid_email');
    $this->form_validation->set_rules('password', 'Password',
                                      'trim|required|xss_clean');
    $this->form_validation->set_rules('confirm-password', 'Confirm Password',
                                      'trim|required|xss_clean|matches[password]');
    $this->form_validation->set_rules('recaptcha_response_field', 'Recaptcha',
                                       'required|callback_check_captcha');

    if ($this->form_validation->run())
    {
      $username = $this->form_validation->set_value('username');
      $email = $this->form_validation->set_value('email');
      $password = $this->form_validation->set_value('password');
      
      $this->sauth->create_user($username, $email, $password);
      
      if ($this->config->item('yay_import') && $this->user_dal->is_yay_username($username))
      {
        $this->send_activate_link($username);
        $view = "auth/registered";
      } else {
        $this->sauth->login($username, $password);
        redirect('/');
      }
    }
    
    $this->load->view('shared/header');
    $this->load->view($view, $data);
    $this->load->view('shared/footer');
  }
  
  function send_activate_link($username)
  {
    $invite_id = random_string('alnum', 32);

	if (!$this->config->item('yay_import')) {
		return FALSE;
	}
    if (!$this->user_dal->create_yh_invite($username, $invite_id)) {
      return FALSE;
    }

    $uri = 'http://' . $_SERVER['SERVER_NAME'] . '/auth/activate/' . $invite_id;

    $message = <<<EOT
      Hey {$username},

      Click this link to activate your yay2.0 account

      {$uri}

     Love,
     The guys at {$this->config->item('email_signature')}
EOT;

      $this->yayhooray->login($this->config->item('yay_username'),
                              $this->config->item('yay_password'));
      $this->yayhooray->send_message($username, 'Activation link', $message);

    return TRUE;
  }

  function forgot_password()
  {
    // set validation for the form
    $this->form_validation->set_rules('email', 'Email', 'required');
    $this->form_validation->set_rules('key', 'Key', 'required');

    // Sends the initial form if a plain GET request
    if (!$this->form_validation->run()) {
      $this->load->view('forgot_password/request', array('error' => ''));
      return;
    }

    // get the values
    $email = $this->form_validation->set_value('email');
    $key = $this->form_validation->set_value('key');

    // make sure the session key matches
    if (!$key === $this->session->userdata('session_id')) {
      return send_json($this->output, 412, array('error' => "invalid key"));
    }

    // find the user
    $user = $this->user_dal->get_user_by_email($email);

    // if user exists
    if (!$user) {
      $err = "Hmm, I couldn't find any accounts with that email address. Are you "
        . "sure that's the one you signed up with?";
      return send_json($this->output, 412, array('error' => $err));
    }

    $passwords = array('airplane', 'apple', 'booger', 'bug', 'burrito',
                       'catapult', 'dude', 'godzilla', 'hamburger',
                       'jabba', 'jacket', 'peach', 'red', 'silly', 'stupid',
                       'sunshine', 'taco', 'threadless', 'wookie', 'yes');

    $password = $passwords[mt_rand(0, 19)] . mt_rand(10, 99) .
      $passwords[mt_rand(0, 19)];
    $data = array('id' => $user->id, 'password' => $password);

    // reset it!
    $this->sauth->reset_password($data);

    $this->email->from($this->config->item('email_addy'), $this->config->item('email_signature'));
    $this->email->to($email);
    $this->email->subject('Your new password!');
    $this->email->message($this->load->view('emails/forgot_password', $data, true));

    $this->email->send();

    return send_json($this->output, 200, array('ok' => true));
  }
  
  /*
   * Callbacks
   */
  
  // callback for username validation rules
  function valid_username($str)
  {
    //return valid_username($str) && $this->user_dal->is_username_available($str)
    if (!valid_username($str))
    {
      $this->form_validation->set_message('valid_username', "Username contains invalid characters");
      return FALSE;
    }
    elseif (!$this->user_dal->is_username_available($str))
    {
      $this->form_validation->set_message('valid_username', "That username is already in use");
      return FALSE;
    }
    
    return TRUE;
  }
  
  // callback for email validation
  function valid_email($str)
  {
    if (!$this->user_dal->is_email_available($str))
    {
      $this->form_validation->set_message('valid_email', "That email address is already in use");
      return FALSE;
    }
    
    return TRUE;
  }
  
  // callback for recaptcha validation
	function check_captcha($val)
  {
	  if ($this->recaptcha->check_answer($this->input->ip_address(), 
                                       $this->input->post('recaptcha_challenge_field'), $val)) {
	    return TRUE;
	  } else {
	    $this->form_validation->set_message('check_captcha', "Captcha incorrect");
	    return FALSE;
	  }
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */