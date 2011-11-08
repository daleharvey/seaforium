<?php

class Users extends Controller {

  function Users()
  {
    parent::Controller();

    $this->load->helper(array('url', 'number', 'content_render'));
    $this->load->model('user_dal');
    $this->load->library('pagination');
  }

  function index($pagination = 0, $username_search_string = '')
  {
    $username_search_string_sql = '';
    if ($username_search_string!='') {
      $username_search_string_sql = " WHERE LOWER(username) regexp '^".strtolower($username_search_string)."'";
    }

    $users_count = $this->user_dal->get_users_count($username_search_string_sql);
    $display = 40;
    $end = min(array($pagination + $display, $users_count));

    // init the pagination library
    $this->pagination->initialize(array(
                                        'base_url' => '/users/',
                                        'total_rows' => $users_count,
                                        'uri_segment' => '2',
                                        'per_page' => $display,
                                        'suffix' => '/'.$username_search_string
                                        ));

    $users = $this->user_dal->get_users($username_search_string_sql, $pagination, $display, (int) $this->session->userdata('user_id'));

    $this->load->view('shared/header');
    $this->load->view('users', array('users' => $users,
						'pagination' => $this->pagination->create_links().'<span class="paging-text">' . ($pagination + 1) . ' - ' . $end . ' of ' . $users_count . ' Users</span>',
						'user_count' => $users_count));
    $this->load->view('shared/footer');
  }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */