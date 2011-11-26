<?php

class Buddies extends Controller {

  function Buddies()
  {
    parent::Controller();

    $this->load->helper(array('form', 'url', 'content_render'));
    $this->load->library('form_validation');

    $this->load->model(array('message_dal', 'user_dal'));
    
    // set all this so we dont have to continually call functions through session
    $this->meta = array(
      'user_id' => (int) $this->session->userdata('user_id'),
      'username' => $this->session->userdata('username'),
      'session_id' => $this->session->userdata('session_id')
    );
  }

  function index($username = ''){

    $this->form_validation->set_rules('username', 'Subject', 'trim|required|xss_clean');
    $this->form_validation->set_rules('command',
                                      'Category', 'required|exact_length[1]|integer');

    if ($this->form_validation->run()) {

      $username = $this->form_validation->set_value('username');
      $acq_id = $this->user_dal->get_user_id_by_username($username);

      //Don't buddy yourself
      if ($acq_id && $this->meta['user_id'] == $acq_id) {
        redirect('/buddies/error/2');
      }

      if ($acq_id && $this->meta['user_id'] != $acq_id) {

        $me = $this->session->userdata('username');
        $key = md5($this->meta['username'] . $acq_id);
        $command = (int)$this->form_validation->set_value('command');

        // check to see if the user is already an acquaintance
        if ($cur_type = $this->user_dal->acquaintance_exists($key)) {
          
          // if we're trying to set an acq of a type that already exists
          if ($cur_type === $command) {
            // nope!
            redirect('/buddies/error/1');
          } else {
            // move the acquaintance to the other type
            $this->user_dal->move_acquaintance($command, $key);
          }
        } else {
          // if the user does not exist, go ahead and set it
          $this->user_dal->add_acquaintance($key, $this->meta['user_id'], $acq_id, $command);
        }

        // if we set him as a buddy, shout it from the mountains
        if ($command === 1) {
          $this->send_buddy_message($acq_id);
        }

        //redirect('/buddies');
      }
    }

    $this->load->view('shared/header');
    $this->load->view('buddies', array(
      'buddies' => $this->user_dal->get_buddies($this->user_id),
      'enemies' => $this->user_dal->get_enemies($this->user_id),
      'username' => str_replace('-', ' ', $username),
      'error_alert' => ''
    ));
    $this->load->view('shared/footer');
  }

  function remove($user_id = 0, $key = '')
  {
    $user_id = (int) $user_id;

    if ($user_id == 0)
      return 0;

    if ($key == $this->session->userdata('session_id')) {
      echo $this->user_dal->delete_acquaintance(
        md5($this->session->userdata('username').$user_id));
      return;
    }
  }
  
  function move($to_list = 'e', $user_id = 0, $key = '')
  {
    $user_id = (int) $user_id;

    if ($user_id == 0)
      return 0;
    
    $to_list = $to_list == 'b' ? 1 : 2;
    
    if ($key == $this->session->userdata('session_id')) {
      echo $this->user_dal->move_acquaintance($to_list,
        md5($this->session->userdata('username').$user_id));
        
        if ($to_list === 1)
          $this->send_buddy_message($user_id);
        
      return;
    }
  }

  function error($error_id = 0)
  {
    $error_id = (int) $error_id;
    $error_alert = '';
    if ($error_id === 1) {
      $error_alert = 'That user is already your buddy/enemy.';
    } elseif ($error_id === 2) {
      $error_alert = 'Please don\'t buddy/enemy yourself.';
    }
    $this->load->view('shared/header');
    $this->load->view('buddies', array(
      'buddies' => $this->user_dal->get_buddies($this->meta['user_id']),
      'enemies' => $this->user_dal->get_enemies($this->meta['user_id']),
      'username' => '',
      'error_alert' => $error_alert
    ));
    $this->load->view('shared/footer');
  }
  
  function send_buddy_message($user_id)
  {
    $profile = $this->config->item('base_url_pm') .'user/' .
      url_title($this->meta['username'], 'dash', TRUE);
    $buddy_link = $this->config->item('base_url_pm') . 'buddies/' .
      url_title($this->meta['username'], 'dash', TRUE);
  
    $message = array(
      'sender' => $this->meta['user_id'],
      'recipient' => $user_id,
      'subject' => $this->meta['username'] .
        " just added you as a buddy",
      'content' => "Wow, what a momentous occasion! Now go return the favour" .
        "...\n\nProfile: <a href=\"" . $profile . "\">" . $profile .
        "</a>\nAdd as buddy: <a href=\"" . $buddy_link."\">".$buddy_link."</a>"
    );
    
    $message['id'] = $this->message_dal->new_message($message);

    $this->message_dal->new_inbox($message['recipient'], $message, '');
  }
}

/* End of file buddies.php */
/* Location: ./application/controllers/buddies.php */
