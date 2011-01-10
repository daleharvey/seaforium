<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller
{
	function Ajax()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('thread_dal');
	}
	
	function thread_notifier($thread_id = 0, $current_count = 0)
	{
		$thread_id = (int)$thread_id;
		$current_count = (int)$current_count;
		
		if ($thread_id === 0 || $current_count === 0)
			return;
		
		$db_count = $this->thread_dal->comment_count($thread_id);
		
		if ($db_count > $current_count)
		{
			$new_posts = $db_count - $current_count;
			
			$goto = ceil($db_count / $this->session->userdata('comments_shown'));
			
			$page = $goto > 0 ? '/p/'.(($goto-1)*$this->session->userdata('comments_shown')) : '';
			
			echo '<div id="notifier"><a href="/thread/'. $thread_id .'/'. url_title($this->thread_dal->get_thread_information($thread_id)->row()->subject, 'dash', TRUE) . $page .'/r'. mt_rand(10000, 99999) .'#bottom">'. $new_posts .' new post'. ($new_posts === 1 ? '' : 's') ."</a></div>";
		}
	}
	
	function comment_souce($comment_id = 0)
	{
		$comment_id = (int)$comment_id;
		
		if ($comment_id === 0)
			return;
		
		$comment = $this->thread_dal->get_comment($comment_id, $this->session->userdata('user_id'));
		
		if ($comment->num_rows())
		{
			echo $comment->row()->content;
		}
		else
		{
			echo 0;
		}
	}
	
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */