<?php

class Users extends Controller {

  function Users()
  {
    parent::Controller();

    $this->load->helper(array('url', 'number', 'content_render'));
    $this->load->model('user_dal');
    $this->load->library('pagination');
  }

  function index()
  {
    // find the user
    $users = $this->user_dal->get_users();

    $this->load->view('shared/header');
    $this->load->view('users', array('users' => $users, "user_count" => 3));
    $this->load->view('shared/footer');
  }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */