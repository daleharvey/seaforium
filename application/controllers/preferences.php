<?php

class Preferences extends Controller {

	function Preferences()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('auth/user_dal');
		
		if (!$this->sauth->is_logged_in())
			redirect('/');
	}
	
	/**
	 * Show/save preferences
	 *
	 * @return void
	 */
	function index()
	{
		$user_id = $this->session->userdata('user_id');
		
		$this->form_validation->set_rules('threads_shown', 'Threads Shown', 'trim|required|is_natural|xss_clean');
		$this->form_validation->set_rules('comments_shown', 'Comments Shown', 'trim|required|is_natural|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|valid_email');
		$this->form_validation->set_rules('random_titles','Show Random Titles', 'trim|xss_clean|integer');
		$this->form_validation->set_rules('new_post_notification','New Post Notification', 'trim|xss_clean|integer');
		$this->form_validation->set_rules('website_1','Website 1', 'trim|xss_clean');
		$this->form_validation->set_rules('website_2','Website 2', 'trim|xss_clean');
		$this->form_validation->set_rules('website_3','Website 3', 'trim|xss_clean');
		$this->form_validation->set_rules('rss_feed_1','Rss Feed 1', 'trim|xss_clean');
		$this->form_validation->set_rules('rss_feed_2','Rss Feed 2', 'trim|xss_clean');
		$this->form_validation->set_rules('rss_feed_3','Rss Feed 3', 'trim|xss_clean');
		$this->form_validation->set_rules('custom_css','Custom CSS', 'trim|xss_clean');
		$this->form_validation->set_rules('about_blurb','Tell us about yourself', 'trim|xss_clean');
		$this->form_validation->set_rules('flickr_username','Flickr Username', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('delicious_username','Del.icio.us Username', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('facebook','Facebook', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('aim','Aim username', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('gchat','Gchat (Jabber)', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('lastfm','Last.fm', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('msn','MSN username', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('real_name','MSN username', 'trim|xss_clean|alpha_dash');
		$this->form_validation->set_rules('location','MSN username', 'trim|xss_clean|alpha_dash');
		
		$this->form_validation->set_rules('password', 'Change Password', 'trim|xss_clean');
		$this->form_validation->set_rules('password2', 'Verify Password', 'trim|xss_clean');
		
		$data['errors'] = array();
		
		if ($this->form_validation->run())
		{
			$data = array(
				'threads_shown' => $this->form_validation->set_value('threads_shown'),
				'comments_shown' => $this->form_validation->set_value('comments_shown'),
				'email' => $this->form_validation->set_value('email'),
				'random_titles' => $this->form_validation->set_value('random_titles'),
				'new_post_notification' => $this->form_validation->set_value('new_post_notification'),
			);
			$data_profile = array (
				'website_1' => $this->form_validation->set_value('website_1'),
				'website_2' => $this->form_validation->set_value('website_2'),
				'website_3' => $this->form_validation->set_value('website_3'),
				'rss_feed_1' => $this->form_validation->set_value('rss_feed_1'),
				'rss_feed_2' => $this->form_validation->set_value('rss_feed_2'),
				'rss_feed_3' => $this->form_validation->set_value('rss_feed_3'),
				'custom_css' => $this->form_validation->set_value('custom_css'),
				'about_blurb' => $this->form_validation->set_value('about_blurb'),
				'flickr_username' => $this->form_validation->set_value('flickr_username'),
				'delicious_username' => $this->form_validation->set_value('delicious_username'),
				'facebook' => $this->form_validation->set_value('facebook'),
				'aim' => $this->form_validation->set_value('aim'),
				'gchat' => $this->form_validation->set_value('gchat'),
				'lastfm' => $this->form_validation->set_value('lastfm'),
				'msn' => $this->form_validation->set_value('msn'),
				'name' => $this->form_validation->set_value('real_name'),
				'location' => $this->form_validation->set_value('location'),
			);
			
			$password = $this->form_validation->set_value('password');
			$password2 = $this->form_validation->set_value('password2');
			
			if (isset($password) && isset($password2) && $password === $password2)
				$this->sauth->reset_password(array('id' => $user_id, 'password' => $password));
			
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('users', $data);
			
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->update('user_profiles', $data_profile);
			
			$this->session->set_userdata($data);
		} else { 
			echo validation_errors();
		}
		
		$query = $this->user_dal->get_profile_information($user_id);
		$data['user_preferences'] = $query->row();
		
		$this->load->view('shared/header');
		$this->load->view('preferences', $data);
		$this->load->view('shared/footer');
		
	}
}

/* End of file preferences.php */
/* Location: ./application/controllers/preferences.php */