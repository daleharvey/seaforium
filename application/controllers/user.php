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
                  ->get_profile_information(str_replace('-', ' ', $username));

		if ($query->result_id->num_rows === 0) {
                  redirect('/');
                }

		$data['user_data'] = $query->row();

                $time_registered =
                  (ceil((time() - strtotime($data['user_data']->created)) / 86400));

                $time_registered = $time_registered <= 0 ? 1 : $time_registered;

                $ppd = ($data['user_data']->thread_count +
                        $data['user_data']->comment_count) / $time_registered;

                $logged_in = date('F jS Y \a\t g:i a',
                                  strtotime($data['user_data']->last_login));

		$data['user_data']->average_posts = number_format($ppd, 2);
		$data['user_data']->last_login_text =
                  (strtotime($data['user_data']->last_login) == null)
                    ? " hasn't logged in yet."
                    : ' last logged in on ' . $logged_in .'.';


		$start = 0;

		$data['recent_posts'] = $this->user_dal
                  ->get_user_recent_posts((int)$data['user_data']->id);

		$this->pagination->initialize(array(
			'base_url' => '/user/' . $data['user_data']->username . '/p/',
			'total_rows' => $data['user_data']->comment_count,
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
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */