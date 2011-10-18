<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->helper(array('form', 'url', 'string'));
    $this->load->library(array('form_validation', 'sauth', 'yayhooray', 'email'));
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
    $this->form_validation->set_rules('username', 'Username', 'trim|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');

    if ($this->form_validation->run()) {
      if ($this->sauth->login($this->form_validation->set_value('username'),
                              $this->form_validation->set_value('password'))) {
        redirect('http://'.$_SERVER['SERVER_NAME']);
        return;
      } else {
        redirect('http://'.$_SERVER['SERVER_NAME']);
        return;
      }
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
    redirect('http://'.$_SERVER['SERVER_NAME']);
  }

  /**
   * Register user on the site
   *
   * @return void
   */
  function register($key = '')
  {
    if ($this->sauth->is_logged_in()) {
      redirect('');
    } else {
      $this->form_validation->set_rules('password', 'Password',
                                        'trim|required|xss_clean');
      $this->form_validation->set_rules('password2', 'Confirm Password',
                                        'trim|required|xss_clean|matches[password]');
      $this->form_validation->set_rules('email', 'Email',
                                        'trim|required|xss_clean|valid_email');
      $this->form_validation->set_rules('key', 'Key', 'trim|required|xss_clean');

      $data['errors'] = array();

      if ($this->form_validation->run()) {
        $username = $this->user_dal->get_username_from_authkey(
          $this->form_validation->set_value('key'));
        if ($username !== 0) {
          if ($this->sauth->create_user($username,
                                        $this->form_validation->set_value('email'),
                                        $this->form_validation->set_value('password'),
                                        $this->form_validation->set_value('key'))) {

            $this->sauth->login($username, $this->form_validation->set_value('password'));

            redirect('');
          } else {
            $data['errors'] = $this->sauth->get_error_message();
          }
        } else {
          $data['errors'] = "No authkey found";
        }
      }

      $data['key'] = $key;

      $this->load->view('shared/header');
      $this->load->view('auth/register', $data);
      $this->load->view('shared/footer');
    }
  }

  function forgot_password()
  {
    // set validation for the form
    $this->form_validation->set_rules('email', 'Email', 'required');
    $this->form_validation->set_rules('key', 'Key', 'required');

    // if the form was actually submitted
    if ($this->form_validation->run()) {
      // get the values
      $email = $this->form_validation->set_value('email');
      $key = $this->form_validation->set_value('key');

      // make sure the session key matches
      if ($key === $this->session->userdata('session_id')) {
        // find the user
        $user = $this->user_dal->get_user_by_email($email);

        // if user exists
        if ($user) {
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
          $data = array('id' => $user->id,
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
        } else {
          $this->load->view('forgot_password/request', array('error' => "Hmm, I couldn't find any accounts with that email address. Are you sure that's the one you signed up with?"));
          }
      }

      // YOU GET NOTHING, SIR!
    } else {
      $this->load->view('forgot_password/request', array('error' => ''));
    }
  }

  /**
   * Invites a user based on their YH username
   *
   * @return void
   *//*
  function invite()
  {
    if ($this->sauth->is_logged_in()) {
      redirect('');
    } else {
      $this->form_validation->set_rules('yhuser', 'Username', 'trim|required|xss_clean|min_length[2]|max_length[18]');

      $data['errors'] = array();

      if ($this->form_validation->run()) {
        $invite_id = random_string('alnum', 32);

        if ($this->sauth->yh_invite($this->form_validation->set_value('yhuser'),
                                    $invite_id)) {
          $message = <<<EOT
            Hey {$this->form_validation->set_value('yhuser')},

            Heres a link for you to register at the new board weve got!

            http://sparklebacon.net/auth/register/{$invite_id}

              castis
         EOT;

         $this->yayhooray->login('castis', '');
         $this->yayhooray->send_message($this->form_validation->set_value('yhuser'), 'Your invite to the new board', $message);

         $data['errors'] = $this->sauth->get_confirmation_message();
        } else {
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