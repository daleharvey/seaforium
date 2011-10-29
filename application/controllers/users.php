<?php

class Users extends Controller {

  function Users()
  {
    parent::Controller();

    $this->load->helper(array('url', 'number', 'content_render'));
    $this->load->model('user_dal');
    $this->load->library('pagination');
  }

  function index($username_search_string = '')
  {
    // find the user
	if ($username_search_string!='') {
		$username_search_string = " WHERE LOWER(username) regexp '^".strtolower($username_search_string)."'";
	}
    $users = $this->user_dal->get_users($username_search_string);

    $this->load->view('shared/header');
    $this->load->view('users', array('users' => $users, "user_count" => 3));
    $this->load->view('shared/footer');
  }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */