<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller
{
  function Ajax()
  {
    parent::__construct();

    $this->load->helper(array('url', 'content_render', 'htmlpurifier'));
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
    if ($thread_id === 0 || $current_count === 0) {
      return;
    }

    // find out how many comments are in the thread
    $thread_info = $this->thread_dal->comment_count_info($thread_id);
    $db_count = $thread_info->max_rows;

    // if the numbers dont match, throw out some html
    if ($db_count > $current_count) {

      // number of new posts
      $new_posts = $db_count - $current_count;
      $title = url_title($thread_info->subject, 'dash', TRUE);
      $shown = $this->session->userdata('comments_shown');
      if (!$shown) {
        $shown = 25;
      }
      $count = (ceil($db_count / $shown) -1) * $shown;

      echo '<div id="notifier"><a id="notify" href="/thread/'. $thread_id .
        '/'. $title . '/p/'. $count .'/r'. mt_rand(10000, 99999) .'#bottom">' .
        $new_posts .' new post'. ($new_posts === 1 ? '' : 's') ." added</a></div>";
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

    if ($comment_id === 0) {
      return;
    }

    $comment = $this->thread_dal->get_comment($comment_id)->row();

    $data = array(
      'content' => $display === 0 ? $comment->content : nl2br($comment->content),
      'owner' => ($comment->user_id == $this->user_id &&
                  strtotime($comment->created) > (time() - 3600))
    );

    echo '('. json_encode($data) .')';
    return;
  }

  function view_source($comment_id = 0)
  {
    if((int)$comment_id === 0) {
      return;
    }

    $comment = $this->thread_dal->get_comment($comment_id)->row();
    $is_first = $this->thread_dal->is_first_comment($comment->thread_id,
                                                    $comment_id);
    $data = array(
      'content' => $comment->original_content,
      'owner' => ($comment->user_id == $this->user_id &&
         ($is_first || strtotime($comment->created) > (time() - (60*60*24))))
    );

    echo '('. json_encode($data) .')';

    return;
  }

  function comment_save()
  {
    $comment_id = (int)$this->input->post('comment_id');

    if ($comment_id === 0) {
      return;
    }

    $existing = $this->thread_dal->get_comment($comment_id)->row();

    if ($existing->user_id === $this->user_id) {

      $content = $this->input->post('content');
      $processed = _process_post($content);

      if ((strtotime($existing->created) > time() - (60 * 60 * 24)) ||
          $this->thread_dal->is_first_comment($existing->thread_id, $comment_id)) {
		    $this->thread_dal->update_comment($comment_id, $content, $processed,
                                              $this->session->userdata('user_id'));
            echo $processed;
      } else {
        echo "Permission Denied";
      }
    }

    return;
  }

  function set_thread_status($thread_id, $keyword, $status, $key)
  {
    if ($key === $this->session->userdata('session_id')) {
      $thread_id = (int) $thread_id;
      $status = (int) $status;
      $user_id = (int) $this->user_id;

      if ($keyword == 'nsfw') {
        echo $this->thread_dal->change_nsfw($user_id, $thread_id, $status);
      } elseif ($keyword == 'closed') {
        echo $this->thread_dal->change_closed($user_id, $thread_id, $status);
      } elseif ($keyword == 'deleted') {
        echo $this->thread_dal->change_deleted($user_id, $thread_id, $status);
      }
    }
  }

  function favorite_thread($thread_id, $key)
  {
    if ($key === $this->session->userdata('session_id')) {
      $thread_id = (int) $thread_id;
      $user_id = (int) $this->user_id;

      $md5 = md5($this->session->userdata('username').$thread_id);

      echo $this->thread_dal->add_favorite($md5, $user_id, $thread_id);
      return;
    }

    echo 0;
  }

  function unfavorite_thread($thread_id, $key)
  {
    if ($key === $this->session->userdata('session_id')) {
      $data = $this->session->userdata('username') . $thread_id;
      echo $this->thread_dal->remove_favorite(md5($data));
      return;
    }

    echo 0;
  }

  function hide_thread($thread_id, $key)
  {
    if ($key == $this->session->userdata('session_id'))
	{
      $thread_id = (int) $thread_id;
      $user_id = (int) $this->user_id;

	  $data = $this->session->userdata('username') . $thread_id;
	  echo $this->thread_dal->add_hide_thread(md5($data), $user_id, $thread_id);
	  return;
	}

	echo 0;
  }

  function unhide_thread($thread_id, $key)
  {
    if ($key == $this->session->userdata('session_id'))
	{
	  $data = $this->session->userdata('username') . $thread_id;
	  echo $this->thread_dal->remove_hide_thread(md5($data));
	  return;
	}

	echo 0;
  }

  function toggle_html($key)
  {
    if ($key === $this->session->userdata('session_id')) {
      $view_html = (int) $this->session->userdata('view_html');
      $user_id = $this->user_id;
      $results = $this->user_dal->toggle_html((int) $user_id, $view_html);

      if ($results === 1) {
        $this->session->set_userdata('view_html', $view_html == 1 ? 0 : 1);
        if ($view_html === 1) {
          echo "Turn on html";
        } else {
          echo "Turn off html";
        }
      }
    }
  }

  function show_desktop($key)
  {
    if ($key === $this->session->userdata('session_id')) {
      $this->session->set_userdata('view_desktop', 1);
      echo "<a href=\"javascript:location.reload();\" id=\"reload_desktop\">Reload the page</a>";
    }
  }

  function show_mobile($key)
  {
    if ($key === $this->session->userdata('session_id')) {
      $this->session->set_userdata('view_desktop', 0);
      echo "<a href=\"javascript:location.reload();\" id=\"reload_mobile\">Reload the page</a>";
    }
  }

  function preview()
  {
    echo _process_post($this->input->post('content'));
  }

}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */