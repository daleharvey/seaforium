<?php

class Thread extends Controller {

  function Thread()
  {
    parent::Controller();

    $this->load->helper(array('url', 'date', 'form', 'content_render',
                              'htmlpurifier'));
    $this->load->library(array('form_validation', 'pagination'));
    $this->load->model('thread_dal');

    // set all this so we dont have to continually call functions through session
    $this->meta = array(
      'user_id' => (int) $this->session->userdata('user_id'),
      'comments_shown' => $this->session->userdata('comments_shown') == false
        ? 50 : (int)$this->session->userdata('comments_shown')
    );
  }

  // if the just throw in /thread into the address bar
  // throw them home
  function index()
  {


  redirect('/');
  }

  function load($thread_id)
  {
    // if they roll in with something unexpected
    // send them home
    if (!is_numeric($thread_id)) {
      redirect('/');
    }

    // grabbing the thread information
    $query = $this->thread_dal->get_thread_information($this->meta['user_id'],
                                                       $thread_id);

    // does it exist?
    if ($query->num_rows === 0)
      redirect('/');

    $thread_info = $query->row();

    $favourites = explode(',',
                          $this->thread_dal->get_favorites($this->meta['user_id']));

    // alright we're clear, set some data for the view
    $data = array(
      'info' => array(
         'title' => $thread_info->subject,
         'nsfw' => $thread_info->nsfw,
         'closed' => $thread_info->closed,
         'category' => $thread_info->category,
         'acq_type' => (int) $thread_info->type,
         'user_id' => $thread_info->user_id,
         'editable' => time() - strtotime($thread_info->created) < 300
       ),
      'thread_id' => $thread_id,
      'favorites' => $favourites
    );


    // if the thread is closed then we're not accepting any new data
    if ($thread_info->closed === '0' || (int) $thread_info->type == 2) {
      // we're going to go ahead and do the form processing for the reply now
      // if they're submitting data, we're going to refresh the page anyways
      // so theres no point in running the query below the form validation
      $this->form_validation->set_rules('content', 'Content', 'required');

      // if a comment was submitted
      if ($this->form_validation->run()) {

        $content = $this->form_validation->set_value('content');

        $this->thread_dal->new_comment(array(
          'thread_id' => $thread_id,
          'user_id' => $this->meta['user_id'],
          'content' => _process_post($content),
          'original_content' => $content
        ));

        $this->user_dal->update_comment_count($this->meta['user_id']);

        $db_count = $this->thread_dal->comment_count($thread_id);
        $shown = $this->session->userdata('comments_shown');
        if (!$shown) {
          $shown = 25;
        }
        $count = (ceil($db_count / $shown) -1) * $shown;

        $url = '/thread/'. $thread_id . '/'.
          url_title($thread_info->subject, 'dash', TRUE) . '/p/'. $count .'#bottom';

        redirect($url);
      }
    }

    $display = $this->session->userdata('comments_shown') == false
      ? 50 : (int)$this->session->userdata('comments_shown');

    $pseg = 0;
    $base_url = '';
    $limit_start = 0;

    for($i=1; $i<=count($this->uri->segments); ++$i) {
      $base_url .= '/'. $this->uri->segments[$i];

      if ($this->uri->segments[$i] == 'p') {
        if (isset($this->uri->segments[$i+1]) &&
            is_numeric($this->uri->segments[$i+1])) {
          $pseg = $i+1;
          $limit_start = (int)$this->uri->segments[$i+1];

          break;
        }
      }
    }

    if ($pseg === 0) {
      $base_url .= '/p';
    }

    $data['thread_model'] =& $this->thread_dal;

    $data['comment_result'] =
      $this->thread_dal->get_comments($this->meta['user_id'],
                                      $thread_id,
                                      $limit_start,
                                      $this->meta['comments_shown']);

    $data['total_comments'] = $this->thread_dal->comment_count($thread_id);

    $this->pagination->initialize(array(
      'num_links' => 4,
      'base_url' => $base_url,
      'total_rows' => $data['total_comments'],
      'uri_segment' => $pseg,
      'per_page' => $this->meta['comments_shown'],
      'full_tag_open' => '<div class="main-pagination">',
      'full_tag_close' => '</div>',
      'cur_tag_open' => '<div class="selected-page">',
      'cur_tag_close' => '</div>',
      'num_tag_open' => '',
      'num_tag_close' => ''
    ));

    $end = min(array($limit_start + $this->meta['comments_shown'],
                     $data['total_comments']));
    $data['pagination'] = $this->pagination->create_links() .
      '<span class="paging-text">'. ($limit_start + 1) .' - '. $end .' of ' .
      $data['total_comments'] .' Posts in <a href="/">Threads</a> &gt; ' .
      '<a href="/f/'.strtolower($data['info']['category']).'">' .
      $data['info']['category'].'</a> > <a href="/thread/'. $thread_id.'/' .
      url_title($data['info']['title'], 'dash', TRUE) .'">' .
      $data['info']['title'].'</a></span>';

    $data['starting'] = $limit_start;

    $this->load->helper('content_render');

    $this->load->view('shared/header', array('page_title' => $thread_info->subject));
    $this->load->view('thread', $data);
    $this->load->view('shared/footer');
  }
}

/* End of file thread.php */
/* Location: ./application/controllers/thread.php */