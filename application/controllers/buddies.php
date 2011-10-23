<?php

class Buddies extends Controller {

	function Buddies()
	{
		parent::Controller();

		$this->load->helper(array('form', 'url', 'content_render'));
		$this->load->library('form_validation');

		$this->load->model(array('message_dal', 'user_dal'));
	}

	function index($username = ''){

		$user_id = (int)$this->session->userdata('user_id');

		$this->form_validation->set_rules('username', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('command', 'Category', 'required|exact_length[1]|integer');

		if ($this->form_validation->run())
		{
			if ($acq_id = $this->user_dal->get_user_id_by_username($this->form_validation->set_value('username')))
			{
				$me = $this->session->userdata('username');
				$key = md5($me.$acq_id);
				$command = (int)$this->form_validation->set_value('command');

				$this->user_dal->add_acquaintance($key, $user_id, $acq_id, $command);

				if ($command === 1)
				{
					$profile = 'http://yayhooray.net/user/'.url_title($me, 'dash', TRUE);
					$buddy_link = 'http://yayhooray.net/buddies/'.url_title($me, 'dash', TRUE);

					$message = array(
						'sender' => $user_id,
						'recipient' => $acq_id,
						'subject' => $this->session->userdata('username')." just added you as a buddy",
						'content' => "Wow, what a momentous occasion! Now go return the favor...\n\nProfile: <a href=\"".$profile."\">".$profile."</a>\nAdd as buddy: <a href=\"".$buddy_link."\">".$buddy_link."</a>"
					);

					$message['id'] = $this->message_dal->new_message($message);

					$this->message_dal->new_inbox($message['recipient'], $message, '');
				}

				redirect('/buddies');
			}
		}

		$this->load->view('shared/header');
		$this->load->view('buddies', array(
			'buddies' => $this->user_dal->get_buddies($user_id),
			'enemies' => $this->user_dal->get_enemies($user_id),
			'username' => $username
		));
		$this->load->view('shared/footer');
	}

	function remove($user_id = 0, $key = '')
	{
		$user_id = (int) $user_id;

		if ($user_id == 0)
			return 0;

		if ($key == $this->session->userdata('session_id'))
		{
			echo $this->user_dal->delete_acquaintance(md5($this->session->userdata('username').$user_id));
			return;
		}
	}
}

/* End of file buddies.php */
/* Location: ./application/controllers/buddies.php */