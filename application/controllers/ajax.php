<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller
{
	function Ajax()
	{
		parent::__construct();

		$this->load->helper(array('url', 'content_render'));
		$this->load->library('form_validation');
		$this->load->model(array('thread_dal', 'user_dal'));
	}

	/**
	 * New Comment Notification
	 *
	 * Javascript calls this every few seconds to check
	 * on the existence of new comments
	 *
	 * @access	public
	 * @param	int		thread id
	 * @param	int		current number of comments
	 * @return	void
	 */
	function thread_notifier($thread_id = 0, $current_count = 0)
	{
		// make sure thread_id and current_count are integers
		$thread_id = (int)$thread_id;
		$current_count = (int)$current_count;

		// the typecasting above will convert non-integers to zero
		if ($thread_id === 0 || $current_count === 0)
			return;

		// find out how many comments are in the thread
		$db_count = $this->thread_dal->comment_count($thread_id);

		// if the numbers dont match, throw out some html
		if ($db_count > $current_count)
		{
			// number of new posts
			$new_posts = $db_count - $current_count;

			/*
			 * TODO: if their comments_shown value is false,
			 * 	they probably aren't logged in, in which case,
			 * 	they don't get the notifier anyways.
			 *
			 * Test out that idea and remove the userdata boolean check accordingly
			 */
			$count = $this->session->userdata('comments_shown') == false
				? 50
				: ceil($db_count / $this->session->userdata('comments_shown'));

			$page = '/p/'. floor($db_count/$count) * $count;

			echo '<div id="notifier"><a id="notify" href="/thread/'. $thread_id .'/'. url_title($this->thread_dal->get_thread_information($this->session->userdata('user_id'), $thread_id)->row()->subject, 'dash', TRUE) .'">'. $new_posts .' new post'. ($new_posts === 1 ? '' : 's') ." added</a></div>";
		}
	}

	/**
	 * Get Comment Source
	 *
	 * Get the raw text for any given comment
	 *
	 * @access	public
	 * @param	int
	 * @param	int
	 * @return	void
	 */
	function comment_source($comment_id = 0, $display = 0)
	{
		$comment_id = (int)$comment_id;

		if ($comment_id === 0)
			return;

		$comment = $this->thread_dal->get_comment($comment_id)->row();

		$data = array(
			'content' => $display === 0 ? $comment->content : nl2br($comment->content),
			'owner' => ($comment->user_id == $this->session->userdata('user_id') && strtotime($comment->created) > (time() - 3600))
		);

		echo '('. json_encode($data) .')';
		return;
	}

	function view_source($comment_id = 0)
	{
		if((int)$comment_id === 0)
			return;

		$comment = $this->thread_dal->get_comment($comment_id)->row();

		$data = array(
			'content' => _ready_for_source($comment->content),
			'owner' => ($comment->user_id == $this->session->userdata('user_id') && strtotime($comment->created) > (time() - 3600))
		);

		echo '('. json_encode($data) .')';

		return;
	}

	function comment_save()
	{
		$comment_id = (int)$this->input->post('comment_id');

		if ($comment_id === 0)
			return;

		$existing = $this->thread_dal->get_comment($comment_id)->row();

		if ($existing->user_id === $this->session->userdata('user_id'))
		{
			$content = _ready_for_save($this->input->post('content'));

			if ($this->thread_dal->update_comment($comment_id, $content, $this->session->userdata('user_id')))
			{
				echo _ready_for_display($content);
			}
		}

		return;
	}

	function set_thread_status($thread_id, $keyword, $status, $key)
	{
		if ($key === $this->session->userdata('session_id'))
		{
			$thread_id = (int) $thread_id;
			$status = (int) $status;
			$user_id = (int) $this->session->userdata('user_id');

			if ($keyword == 'nsfw')
			{
				echo $this->thread_dal->change_nsfw($user_id, $thread_id, $status);
			}
			elseif ($keyword == 'closed')
			{
				echo $this->thread_dal->change_closed($user_id, $thread_id, $status);
			}
		}
	}

	function favorite_thread($thread_id, $key)
	{
		if ($key === $this->session->userdata('session_id'))
		{
			$thread_id = (int) $thread_id;
			$user_id = (int) $this->session->userdata('user_id');

			$md5 = md5($this->session->userdata('username').$thread_id);

			echo $this->thread_dal->add_favorite($md5, $user_id, $thread_id);
			return;
		}

		echo 0;
	}

	function unfavorite_thread($thread_id, $key)
	{
		if ($key === $this->session->userdata('session_id'))
		{
			echo $this->thread_dal->remove_favorite(md5($this->session->userdata('username').$thread_id));
			return;
		}

		echo 0;
	}

	function toggle_html($key)
	{
		if ($key === $this->session->userdata('session_id'))
		{
			$view_html = (int) $this->session->userdata('view_html');
			$results = $this->user_dal->toggle_html((int) $this->session->userdata('user_id'), $view_html);

			if ($results === 1)
			{
				$this->session->set_userdata('view_html', $view_html == 1 ? 0 : 1);
				if ($view_html === 1)
				{
					echo "Turn on html";
				}
				else
				{
					echo "Turn off html";
				}
			}
		}
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */