<?php

class Thread extends Controller {

  function Thread()
  {
    parent::__construct();

    $this->load->helper(array('url', 'date', 'form', 'content_render', 'htmlpurifier'));
    $this->load->library(array('form_validation', 'pagination'));
    $this->load->model('thread_dal');
		$this->load->model('thread_model');
  }

  // if the just throw in /thread into the address bar
  // throw them home
  function index() {
    redirect('/');
  }

  function load($thread_id)
  {
    $segments = $this->uri->segment_array();

    while($seg = next($segments))
      $page = $seg == 'p' ? (int) next($segments) : 0;

    $thread = $this->thread_model->get_thread($thread_id, $this->meta, $page);

    $uri = '/thread/'. $thread_id .'/'.
      url_title($thread->information->subject, 'dash', TRUE);

    // if they roll in with something unexpected
    // or the thread doesnt exist
    // send them home
    if ($thread == null)
      redirect('/');

    // if the thread is closed then we're not accepting any new data
    if (!$thread->information->closed ||
        $thread->information->author_acquaintance_type == 2) {
      // we're going to go ahead and do the form processing for the reply now
      // if they're submitting data, we're going to refresh the page anyways
      // so theres no point in running the query below the form validation
      $this->form_validation->set_rules('content', 'Content', 'required');
      $this->form_validation->set_rules('ajax', 'ajax');

      // if a comment was submitted
      if ($this->form_validation->run()) {

        $content = $this->form_validation->set_value('content');
        $ajax = $this->form_validation->set_value('ajax');

        $this->thread_model->new_comment((object) array(
          'thread_id' => $thread_id,
          'user_id' => $this->meta['user_id'],
          'content' => _process_post($content),
          'original_content' => $content
        ));

        $this->user_dal->update_comment_count($this->meta['user_id']);

        $shown = $this->meta['comments_shown'];

        $last_page = (ceil(($thread->information->comment_count + 1) /
                           $this->meta['comments_shown'])
                      * $this->meta['comments_shown']) - $this->meta['comments_shown'];

        // Append some unique junk to make sure the page path is different,
        // otherwise wont redirecr
        $redirection = $uri .'/p/'. $last_page .'/'. '#bottom';

        if ($ajax) {
          return send_json($this->output, 201, array('ok' => true, 'url' =>  $redirection));
        } else {
          redirect($redirection);
        }
      }
    }

    $this->pagination->initialize(array(
      'num_links' => 3,
      'base_url' => $uri .= '/p/',
      'total_rows' => $thread->information->comment_count,
      'uri_segment' => 5,
      'per_page' => $this->meta['comments_shown'],
      'full_tag_open' => '<div class="main-pagination">',
      'full_tag_close' => '</div>',
      'cur_tag_open' => '<div class="selected-page">',
      'cur_tag_close' => '</div>',
      'num_tag_open' => '',
      'num_tag_close' => ''
    ));

    $uri .= isset($uri_assoc['p']) ? (int) $uri_assoc['p'] : 0;

    $thread->pagination = (object) array(
      'links' => $this->pagination->create_links(),
      'lower_limit' => $page + 1,
      'upper_limit' => min(array($page + $this->meta['comments_shown'],
                                 $thread->information->comment_count)),
      'category' => '<a href="/f/'. strtolower($thread->information->category) .'">'.
        $thread->information->category .'</a>',
      'thread' => '<a href="/thread/'. $thread_id .'/'.
        url_title($thread->information->subject, 'dash', TRUE) .'">'.
        $thread->information->subject .'</a>'
    );

    $thread->information->page = $page;

    $this->load->helper('content_render');

    $this->load->view('shared/header', array('page_title' => $thread->information->subject));
    $this->load->view('thread', $thread);
    $this->load->view('shared/footer');
  }
}

/* End of file thread.php */
/* Location: ./application/controllers/thread.php */