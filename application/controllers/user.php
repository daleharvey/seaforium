<?php

class User extends Controller {

  function User()
  {
    parent::Controller();

    $this->load->helper(array('url', 'number', 'content_render'));
    $this->load->model('user_dal');
    $this->load->library('pagination');
  }

  function index()
  {
    redirect('/');
  }

  function load($username)
  {
    $query = $this->user_dal
      ->get_profile_information(str_replace('-', ' ', $username), (int) $this->session->userdata('user_id'));

    if ($query->result_id->num_rows === 0) {
      show_404('/user/'.$username);
    }

    $data['user_data'] = $query->row();

	if (is_null($data['user_data']->username)||$data['user_data']->username=='') {
		//redirect('/');
		show_404('/user/'.$username);
	}

    $time_registered =
      (ceil((time() - strtotime($data['user_data']->created)) / 86400));

    $time_registered = $time_registered <= 0 ? 1 : $time_registered;

    $ppd = ($data['user_data']->threads_count +
            $data['user_data']->comments_count) / $time_registered;

    $logged_in = date('F jS Y \a\t g:i a',
                      strtotime($data['user_data']->last_login));

    $data['user_data']->average_posts = number_format($ppd, 2);
    $data['user_data']->last_login_text =
      (strtotime($data['user_data']->last_login) == null)
      ? " hasn't logged in yet."
      : ' last logged in on ' . $logged_in .'.';

	$data['user_data']->online_status = ((int) $data['user_data']->latest_activity) > (time() - 300) ? 'ONLINE' : 'NOT ONLINE';

	$data['user_data']->friendly_status = "";
	if ($data['user_data']->buddy_check == '1')
	{
		$data['user_data']->friendly_status = "BUDDY";
	} elseif ($data['user_data']->enemy_check == '1')
	{
		$data['user_data']->friendly_status = "IGNORED";
	}

    $data['recent_posts'] = $this->user_dal
      ->get_user_recent_posts((int)$data['user_data']->id);

	$data['buddy_count'] = $this->user_dal
	  ->get_buddies_count((int)$data['user_data']->id);

	$data['enemy_count'] = $this->user_dal
	  ->get_enemies_count((int)$data['user_data']->id);

    $this->pagination->initialize(array(
      'base_url' => '/user/' . $data['user_data']->username . '/p/',
      'total_rows' => $data['user_data']->comments_count,
      'uri_segment' => '4',
      'per_page' => $data['user_data']->comments_shown,
      'full_tag_open' => '<div class="main-pagination">',
      'full_tag_close' => '</div>',
      'cur_tag_open' => '<div class="selected-page">',
      'cur_tag_close' => '</div>',
      'num_tag_open' => '',
      'num_tag_close' => ''
    ));


    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('shared/header');
    $this->load->view('user', $data);
    $this->load->view('shared/footer');
  }

  function check($username, $filter, $pagination = 0)
  {
    switch($filter)
    {
      case 'enemyof':
        $type = 2;
        break;
      case 'buddyof':
        $type = 1;
        break;
      default:
        $type = 1;
        $filter = 'buddyof';
    }


    $users_count = $this->user_dal->get_acquaintance_count($username, $type);
    $display = 40;
    $end = min(array($pagination + $display, $users_count));

    $this->pagination->initialize(array(
                                        'base_url' => '/user/'. $username .'/'. $filter .'/p/',
                                        'total_rows' => $users_count,
                                        'uri_segment' => '2',
                                        'per_page' => $display
                                        ));

    $users = $this->user_dal->get_acquaintance_information($username, $type, (int)$pagination, $display);

    $this->load->view('shared/header');
    $this->load->view('acquaintances', array('users' => $users,
						'pagination' => $this->pagination->create_links().'<span class="paging-text">' . ($pagination + 1) . ' - ' . $end . ' of ' . $users_count . ' Users</span>',
						'user_count' => $users_count,
            'type' => $type));
    $this->load->view('shared/footer');

  }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */