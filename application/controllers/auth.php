<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Controller
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
    $this->sauth->logout();
    return redirect('/');
  }

  /**
   * Register user on the site
   *
   * @return void
   */
  function register()
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

    if (!valid_username($username)) {
      return send_json($this->output, 401, array('error' => 'invalid username'));
    }

    if (!$this->sauth->create_user($username, $email, $password)) {
      return send_json($this->output, 412, array('error' => $this->sauth->error));
    }

    if ($this->user_dal->is_yay_username($username)) {
      $this->send_activate_link($username);
      return send_json($this->output, 201, array('ok' => true, 'method' => 'yaypm'));
    }

    $this->sauth->login($username, $password);
    return send_json($this->output, 201, array('ok' => true, 'method' => 'plain'));
  }


  function send_activate_link($username)
  {
    $invite_id = random_string('alnum', 32);

    if (!$this->user_dal->create_yh_invite($username, $invite_id)) {
      return FALSE;
    }

    $uri = 'http://' . $_SERVER['SERVER_NAME'] . '/auth/activate/' . $invite_id;

    $message = <<<EOT
      Hey {$username},

      Click this link to activate your yay2.0 account

      {$uri}

      dh
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

    $this->email->from('dale@arandomurl.com', 'YayHooray.net');
    $this->email->to($email);
    $this->email->subject('Your new password!');
    $this->email->message($this->load->view('emails/forgot_password', $data, true));

    $this->email->send();

    return send_json($this->output, 200, array('ok' => true));
  }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */