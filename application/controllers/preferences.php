<?php

require_once(APPPATH . '/libraries/phpass-0.1/PasswordHash.php');

class Preferences extends Controller {

  function Preferences()
  {
    parent::Controller();

    $this->load->helper(array('form', 'url', 'utils'));
    $this->load->library('form_validation');
    $this->load->model('auth/user_dal');

    if (!$this->sauth->is_logged_in()) {
      redirect('/');
    }
  }

  /**
   * Show/save preferences
   *
   * @return void
   */
  function index()
  {
    $user_id = $this->session->userdata('user_id');
    $user = $this->user_dal->get_user_by_id($user_id);

    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

    $this->form_validation->set_rules('threads_shown', 'Threads Shown',
                                      'trim|required|is_natural|xss_clean');
    $this->form_validation->set_rules('comments_shown', 'Comments Shown',
                                      'trim|required|is_natural|xss_clean');
    $this->form_validation->set_rules('email', 'Email Address',
                                      'trim|xss_clean|valid_email');
    $this->form_validation->set_rules('random_titles','Show Random Titles',
                                      'trim|xss_clean|integer');
    $this->form_validation->set_rules('new_post_notification',
                                      'New Post Notification',
                                      'trim|xss_clean|integer');
    $this->form_validation->set_rules('hide_enemy_posts',
                                      'Hide Enemy Posts',
                                      'trim|xss_clean|integer');
    $this->form_validation->set_rules('website_1','Website 1', 'trim|xss_clean');
    $this->form_validation->set_rules('website_2','Website 2', 'trim|xss_clean');
    $this->form_validation->set_rules('website_3','Website 3', 'trim|xss_clean');
    $this->form_validation->set_rules('rss_feed_1','Rss Feed 1', 'trim|xss_clean');
    $this->form_validation->set_rules('rss_feed_2','Rss Feed 2', 'trim|xss_clean');
    $this->form_validation->set_rules('rss_feed_3','Rss Feed 3', 'trim|xss_clean');
    $this->form_validation->set_rules('custom_css','Custom CSS', 'trim|xss_clean');
    $this->form_validation->set_rules('about_blurb','Tell us about yourself',
                                      'trim|xss_clean');
    $this->form_validation->set_rules('flickr_username','Flickr Username',
                                      'trim|xss_clean');
    $this->form_validation->set_rules('delicious_username', 'Del.icio.us Username',
                                      'trim|xss_clean');
    $this->form_validation->set_rules('facebook','Facebook', 'trim|xss_clean');
    $this->form_validation->set_rules('aim','Aim username', 'trim|xss_clean');
    $this->form_validation->set_rules('gchat','Gchat (Jabber)', 'trim|xss_clean');
    $this->form_validation->set_rules('lastfm','Last.fm', 'trim|xss_clean');
    $this->form_validation->set_rules('msn','MSN username', 'trim|xss_clean');
    $this->form_validation->set_rules('twitter','Twitter username', 'trim|xss_clean');
    $this->form_validation->set_rules('real_name','MSN username', 'trim|xss_clean');
    $this->form_validation->set_rules('location','MSN username', 'trim|xss_clean');
    $this->form_validation->set_rules('password', 'Change Password',
                                      'trim|xss_clean');
    $this->form_validation->set_rules('password2', 'Verify Password',
                                      'trim|xss_clean');
    $this->form_validation->set_rules('old_password', 'Old Password',
                                      'trim|xss_clean');
    $error = false;

    if ($this->form_validation->run()) {
      $data = array(
        'threads_shown' => $this->form_validation->set_value('threads_shown'),
        'comments_shown' => $this->form_validation->set_value('comments_shown'),
        'email' => $this->form_validation->set_value('email'),
        'random_titles' => $this->form_validation->set_value('random_titles'),
        'custom_css' => $this->form_validation->set_value('custom_css'),
        'new_post_notification' =>
          $this->form_validation->set_value('new_post_notification'),
        'hide_enemy_posts' =>
          $this->form_validation->set_value('hide_enemy_posts'),
      );
      $data_profile = array (
        'website_1' => make_link($this->form_validation->set_value('website_1')),
        'website_2' => make_link($this->form_validation->set_value('website_2')),
        'website_3' => make_link($this->form_validation->set_value('website_3')),
        'rss_feed_1' => make_link($this->form_validation->set_value('rss_feed_1')),
        'rss_feed_2' => make_link($this->form_validation->set_value('rss_feed_2')),
        'rss_feed_3' => make_link($this->form_validation->set_value('rss_feed_3')),
        'about_blurb' => $this->form_validation->set_value('about_blurb'),
        'flickr_username' => $this->form_validation->set_value('flickr_username'),
        'delicious_username' => $this->form_validation->set_value('delicious_username'),
        'facebook' => $this->form_validation->set_value('facebook'),
        'aim' => $this->form_validation->set_value('aim'),
        'gchat' => $this->form_validation->set_value('gchat'),
        'lastfm' => $this->form_validation->set_value('lastfm'),
        'msn' => $this->form_validation->set_value('msn'),
        'twitter' => $this->form_validation->set_value('twitter'),
        'name' => $this->form_validation->set_value('real_name'),
        'location' => $this->form_validation->set_value('location'),
      );

      if (isset($_FILES['emot_upload']) &&
          strlen($_FILES['emot_upload']['name']) > 0) {
        $this->load->library('upload', array(
          'upload_path' => './img/emoticons/',
          'allowed_types' => 'gif|png',
          'max_size' => 20,
          'max_width' => 16,
          'max_height' => 16,
          'overwrite' => TRUE,
          'encrypt_name' => TRUE
        ));

        if ($this->upload->do_upload('emot_upload')) {
          $uploaded = $this->upload->data();
          if (rename($uploaded['full_path'],
                     $uploaded['file_path'] . $user_id . $uploaded['file_ext']))
            $data['emoticon'] = 1;
        } else {
          $error = $this->upload->display_errors();
        }
      }

      $old_password = $this->form_validation->set_value('old_password');
      $password = $this->form_validation->set_value('password');
      $password2 = $this->form_validation->set_value('password2');

      if (isset($old_password) || isset($password) || isset($password2)) {

        if (strlen($password) < 3) {
          $error = "Your password must be 3 characters at least";
        } else if ($password !== $password2) {
          $error = "Your passwords do not match";
        } else {
          $hasher = new PasswordHash(8, FALSE);
          if (!$hasher->CheckPassword($old_password, $user->password)) {
            $error = "Incorrect password";
          } else {
            $this->sauth->reset_password(array(
              'id' => $user_id,
              'password' => $password
            ));
            $error = "Your password has been changed";
          }
        }
      }

      $this->db->where('id', $this->session->userdata('user_id'));
      $this->db->update('users', $data);

      $this->db->where('user_id', $this->session->userdata('user_id'));
      $this->db->update('user_profiles', $data_profile);

      $this->session->set_userdata($data);
    }

    $query = $this->user_dal->get_profile_information_by_id($user_id);
    $data['user_preferences'] = $query->row();
    $data['error'] = $error;

    $this->load->view('shared/header');
    $this->load->view('preferences', $data);
    $this->load->view('shared/footer');

  }
}

/* End of file preferences.php */
/* Location: ./application/controllers/preferences.php */