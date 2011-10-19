<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Beta extends Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->helper(array('form', 'url', 'string', 'utils'));
    $this->load->library(array('form_validation', 'sauth', 'yayhooray', 'email'));
    $this->load->model('user_dal');
  }

  function index()
  {
    $this->load->view('beta/header');
    $this->load->view('beta/footer');
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
      return send_json($this->output, 401, array('error' => 'error logging in'));
    }

    return send_json($this->output, 200, array('ok' => true));
  }

  /**
   * Logout user
   *
   * @return void
   */
  function logout()
  {
    $this->sauth->logout();
    redirect('http://'.$_SERVER['SERVER_NAME']);
    return;
  }

  /**
   * Register user on the site
   *
   * @return void
   */
  function register($key = '')
  {
    if ($this->sauth->is_logged_in()) {
      return send_json($this->output, 412, array('error' => 'already logged in'));
    }

    $this->form_validation->set_rules('username', 'usename',
                                      'trim|required|xss_clean');
    $this->form_validation->set_rules('email', 'Email',
                                      'trim|required|xss_clean|valid_email');
    $this->form_validation->set_rules('password', 'Password',
                                      'trim|required|xss_clean');
    $this->form_validation->set_rules('password_confirm', 'Confirm Password',
                                      'trim|required|xss_clean|matches[password]');

    if (!$this->form_validation->run()) {
      return send_json($this->output, 401, array('error' => 'invalid login details'));
    }

    $username = $this->form_validation->set_value('username');
    $email = $this->form_validation->set_value('email');
    $password = $this->form_validation->set_value('password');

    if (!$this->sauth->create_user($username, $email, $password)) {
      return send_json($this->output, 412, array('error' => $this->sauth->error));
    }

    $this->sauth->login($username, $password);
    return send_json($this->output, 201, array('ok' => true));
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

    // make some data to throw at auth
    $password = $passwords[mt_rand(0, 19)] . mt_rand(10, 99) .
      $passwords[mt_rand(0, 19)];
    $data = array('id' => $user->id, 'password' => $password);

    // reset it!
    $this->sauth->reset_password($data);

    $this->email->from('castis@gmail.com', 'YayHooray.net');
    $this->email->to($email);
    $this->email->subject('Your new password!');
    $this->email->message($this->load->view('emails/forgot_password', $data, true));

    $this->email->send();

    return send_json($this->output, 200, array('ok' => true));
  }

  /**
   * Invites a user based on their YH username
   *
   * @return void
   */
  /*
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
  */
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */