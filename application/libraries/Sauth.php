<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

class Sauth
{
  public $error = array();
  public $confirmation = array();

  function __construct()
  {
    $this->ci =& get_instance();

    $this->ci->load->database();
    $this->ci->load->model('user_dal');

    $this->ci->user_id = NULL;

    // try to autologin
    $this->autologin();
  }

  /**
   * Log the user in
   *
   * @param	string
   * @param	string
   * @return	bool
   */
  function login($username, $password)
  {
    if (is_null($user = $this->ci->user_dal->get_user_by_username($username))) {
      $this->increase_login_attempt($username);
      $this->error = array('msg' => "Login incorrect");
      return FALSE;
    }

    $hasher = new PasswordHash(8, FALSE);

    if (!$hasher->CheckPassword($password, $user->password)) {
      $this->increase_login_attempt($username);
      $this->error = array('msg' => "Login incorrect");
      return FALSE;
    }

    if ($user->banned == 1) {
      $this->error = array('msg' => $user->ban_reason);
      return FALSE;
    }
    if ($user->activated == 0) {
      $this->error = array('msg' => "User Account is not active");
      return FALSE;
    }

    $data = array(
      'user_id' => $user->id,
      'username' => $user->username,
      'status' => ($user->activated == 1) ? 1 : 0,
      'threads_shown' => $user->threads_shown,
      'hide_enemy_posts' => $user->hide_enemy_posts,
      'comments_shown' => $user->comments_shown,
      'view_html' => $user->view_html,
      'new_post_notification' => $user->new_post_notification,
      'random_titles' => $user->random_titles,
      'emoticon' => $user->emoticon,
      'hide_ads' => $user->hide_ads,
      'chat_fixed_size' => $user->chat_fixed_size
    );

    $this->ci->session->set_userdata($data);
    $this->ci->user_id = (int)$user->id;

    $this->create_autologin($user->id);

		$this->ci->user_dal->insert_ip_address($user->id, $this->ci->input->ip_address());

    $this->clear_login_attempts($username);

    $ip = $this->ci->config->item('login_record_ip', 'auth');
    $time = $this->ci->config->item('login_record_time', 'auth');
    $this->ci->user_dal->update_login_info($user->id, $ip, $time);

    return TRUE;
  }

  /**
   * Logout user from the site
   *
   * @return	void
   */
  function logout()
  {
    $data = array('user_id' => '', 'username' => '', 'status' => '');

    $this->delete_autologin();
    $this->ci->session->set_userdata($data);
    $this->ci->session->sess_destroy();
  }

  /**
   * Insert a new yh invite record into the database
   *
   * @return	void
   */
  function yh_invite($username, $invite_id)
  {
    $this->ci->user_dal->create_yh_invite($username, $invite_id);
    return TRUE;
  }

  /**
   * Create new user on the site
   *
   * @param	string
   * @param	string
   * @param	string
   * @param	string
   * @return	bool
   */
  function create_user($username, $email, $password)
  {
    $hasher = new PasswordHash(8, FALSE);
    $user = array(
      'username' => $username,
      'password' => $hasher->HashPassword($password),
      'email' => $email,
      'last_ip' => $this->ci->input->ip_address(),
      'activated' => 1
    );

    // insert the user into the database
    $user_id = $this->ci->user_dal->create_user($user);

    return TRUE;
  }

  function reset_password($data)
  {
    $hasher = new PasswordHash(8, FALSE);

    $this->ci->user_dal->reset_password($data['id'],
                                        $hasher->HashPassword($data['password']));
  }

  /**
   * Check if user logged in. Also test if user is activated or not.
   *
   * @param	bool
   * @return	bool
   */
  function is_logged_in($activated = TRUE)
  {
    return $this->ci->session->userdata('status') === ($activated ? 1 : 0);
  }

  /**
   * Get user_id
   *
   * @return	string
   */
  function get_user_id()
  {
    return $this->ci->session->userdata('user_id');
  }

  /**
   * Get error message.
   * Can be invoked after any failed operation such as login or register.
   *
   * @return	string
   */
  function get_error_message()
  {
    return $this->error;
  }

  /**
   * Get confirmation message.
   *
   * @return	string
   */
  function get_confirmation_message()
  {
    return $this->confirmation;
  }

  /**
   * Save data for user's autologin
   *
   * @param	int
   * @return	bool
   */
  private function create_autologin($user_id)
  {
    $this->ci->load->helper('cookie');
    $key = substr(md5(uniqid(rand().get_cookie('session'))), 0, 16);

    $this->ci->load->model('auth/user_autologin');
    $this->ci->user_autologin->purge($user_id);

    if ($this->ci->user_autologin->set($user_id, md5($key))) {
      set_cookie(array(
        'name' => 'autologin',
        'value'  => serialize(array('user_id' => $user_id, 'key' => $key)),
        'expire' => 5356800,
      ));
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Clear user's autologin data
   *
   * @return	void
   */
  private function delete_autologin()
  {
    $this->ci->load->helper('cookie');
    if ($cookie = get_cookie('autologin', TRUE)) {

      $data = unserialize($cookie);

      $this->ci->load->model('auth/user_autologin');
      $this->ci->user_autologin->delete($data['user_id'], md5($data['key']));

      delete_cookie('autologin');
    }
  }

  /**
   * Login user automatically if he/she provides correct autologin verification
   *
   * @return	bool
   */
  private function autologin()
  {
    // not logged in (as any user)
    if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE)) {

      $this->ci->load->helper('cookie');

      if ($cookie = get_cookie('autologin', TRUE)) {

        $data = unserialize($cookie);

        if (isset($data['key']) AND isset($data['user_id'])) {

          $this->ci->load->model('auth/user_autologin');
          $user = $this->ci->user_dal->get_user_by_id($data['user_id']);

          if ($user) {
            $data = array(
              'user_id' => $user->id,
              'username' => $user->username,
              'status' => ($user->activated == 1) ? 1 : 0,
              'threads_shown' => $user->threads_shown,
              'hide_enemy_posts' => $user->hide_enemy_posts,
              'comments_shown' => $user->comments_shown,
              'view_html' => $user->view_html,
              'new_post_notification' => $user->new_post_notification,
              'random_titles' => $user->random_titles,
              'emoticon' => $user->emoticon,
              'custom_css' => $user->custom_css
            );

            // This should just be global data, does not need to go through
            // cookies as it is read on every page request
            $this->ci->session->set_userdata($data);
            $this->ci->user_id = (int)$user->id;

            $ip = $this->ci->config->item('login_record_ip', 'auth');
            $time = $this->ci->config->item('login_record_time', 'auth');
            $this->ci->user_dal->update_login_info($user->id, $ip, $time);
            return TRUE;
          }
        } else {
          return FALSE;
        }
      }
    }
    return FALSE;
  }

  /**
   * Check if login attempts exceeded max login attempts (specified in config)
   *
   * @param	string
   * @return	bool
   */
  function is_max_login_attempts_exceeded($login)
  {
    $this->ci->load->model('auth/login_attempts');
    return $this->ci->login_attempts
      ->get_attempts_num($this->ci->input->ip_address(), $login) >= 5;
  }

  /**
   * Increase number of attempts for given IP-address and login
   * (if attempts to login is being counted)
   *
   * @param	string
   * @return	void
   */
  private function increase_login_attempt($login)
  {
    if (!$this->is_max_login_attempts_exceeded($login)) {
      $this->ci->load->model('auth/login_attempts');
      $this->ci->login_attempts
        ->increase_attempt($this->ci->input->ip_address(), $login);
    }
  }

  /**
   * Clear all attempt records for given IP-address and login
   * (if attempts to login is being counted)
   *
   * @param	string
   * @return	void
   */
  private function clear_login_attempts($login)
  {
    $this->ci->load->model('auth/login_attempts');
    $this->ci->login_attempts->clear_attempts($this->ci->input->ip_address(),
                                              $login, 86400);
  }
}

/* End of file sauth.php */
/* Location: ./application/libraries/auth.php */