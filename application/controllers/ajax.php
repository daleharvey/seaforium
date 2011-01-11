<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller
{
	function Ajax()
	{
		parent::__construct();
		
		$this->load->helper(array('url', 'content_render'));
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
			
      print_r($this->session->userdata('comments_shown'));
			$goto = $this->session->userdata('comments_shown') == 0 
        ? 0 
        : ceil($db_count / $this->session->userdata('comments_shown'));
			
			$page = $goto > 0 ? '/p/'.(($goto-1)*$this->session->userdata('comments_shown')) : '';
			
			echo '<div id="notifier"><a href="/thread/'. $thread_id .'/'. url_title($this->thread_dal->get_thread_information($thread_id)->row()->subject, 'dash', TRUE) . $page .'/r'. mt_rand(10000, 99999) .'#bottom">'. $new_posts .' new post'. ($new_posts === 1 ? '' : 's') ."</a></div>";
		}
	}
	
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
	}
	
	function comment_save()
	{
		$comment_id = (int)$_POST['comment_id'];
		
		if ($comment_id === 0)
			return;
		
		$existing = $this->thread_dal->get_comment($comment_id)->row();
		
		if ($existing->user_id === $this->session->userdata('user_id'))
		{
			$content = _ready_for_save($this->input->xss_clean($_POST['content']));
			
			if ($this->thread_dal->update_comment($comment_id, $content, $this->session->userdata('user_id')))
			{
				echo $content;
			}
		}
		
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */