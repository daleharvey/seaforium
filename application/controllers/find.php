<?php

class Find extends Controller {

  function Find()
  {
    parent::Controller();
	$this->load->library(array('form_validation', 'pagination'));
    $this->load->helper(array('url', 'date', 'form', 'content_render', 'htmlpurifier'));

    $this->load->model('thread_dal');

    // set all this so we dont have to continually call functions through session
    $this->meta = array(
      'user_id' => (int) $this->session->userdata('user_id'),
      'comments_shown' => $this->session->userdata('comments_shown'),
      'hide_enemy_posts' => $this->session->userdata('hide_enemy_posts'),
      'threads_shown' => $this->session->userdata('threads_shown')
    );

  }


  function index($pagination = 0, $filter = '', $ordering = '', $dir = 'desc')
  {

   $this->load->view('shared/header', array('page_title' => 'Searching'));

  // echo "<pre style='margin-left: 300px'>";
   if($this->uri->segment(2)) {





	$search_phrase = $this->uri->segment(2);
    // get a thread count from the database


    // how many threads per page
    $display = $this->meta['threads_shown'] == false ? 50 : $this->meta['threads_shown'];
	 $filtering = $this->_ready_filters($filter, $ordering, $dir);
	$thread_count = $this->thread_dal->find_thread_by_title_rows($search_phrase);
    // init the pagination library
    $this->pagination->initialize(array(
                                        'base_url' => '/p/',
                                        'total_rows' => $thread_count,
                                        'uri_segment' => '2',
                                        'per_page' => $display,
                                        'suffix' => ''
                                        ));


    // end of threads
    $end = min(array($pagination + $display, $thread_count));

    $this->load->view('threads', array(
                                       'title' => $this->thread_dal->get_front_title(),
                                       'thread_result' => $this->thread_dal->find_thread_by_title($this->meta['user_id'], $pagination, $display, $filtering['filter'], $filtering['order'], $search_phrase),
                                       'pagination' => $this->pagination->create_links()
                                       .'<span class="paging-text">' . ($pagination + 1) . ' - ' . $end . ' of ' . $thread_count . ' Threads</span>',
                                       'tab_links' => strlen($filter) > 0 ? '/f/'.$filter.'/' : '/o/',
                                       'tab_orders' => array(
                                                             'started' => $ordering == 'started' && $dir == 'desc' ? 'asc' : 'desc',
                                                             'latest' => $ordering == 'latest' && $dir == 'desc' ? 'asc' : 'desc',
                                                             'posts' => $ordering == 'posts' && $dir == 'desc' ? 'asc' : 'desc'
                                                             ),
                                       'favorites' => explode(',', $this->thread_dal->get_favorites($this->meta['user_id'])),
                                       'hidden_threads' => explode(',', $this->thread_dal->get_hidden($this->meta['user_id']))
                                       ));


	} else {
		echo "derp";
	}
	//echo "</pre>";
	$this->load->view('shared/footer');
  }

  function _ready_filters($filter, $ordering, $dir)
  {
    $filter = $sql = '';
    $ordering = $dir = '';

    return array(
      'filter' => $sql,
      'order' => $sql_order,
      'url_suffix' => (strlen($filter) > 0 ? '/'.$filter : '')
        . (strlen($ordering) > 0 ? '/'.$ordering : '')
        . (strlen($dir) > 0 ? '/'.$dir : '')
    );
  }
}

/* End of file find.php */
/* Location: ./application/controllers/find.php */