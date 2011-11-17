<?php

class Welcome extends Controller {

  var $meta;

  function Welcome()
  {
    parent::Controller();

    // load up some external help
    $this->load->helper(array('date', 'url'));
    $this->load->library('pagination');
    $this->load->model('thread_dal');

    // set all this so we dont have to continually call functions through session
    $this->meta = array(
      'user_id' => (int) $this->session->userdata('user_id'),
      'username' => $this->session->userdata('username'),
      'hide_enemy_posts' => $this->session->userdata('hide_enemy_posts'),
      'threads_shown' => $this->session->userdata('threads_shown')
    );
  }

  function index($pagination = 0, $filter = '', $ordering = '', $dir = 'desc', $whostarted = '')
  {
	// uncomment the following line you if broke something but you can't figure out what.
	//$this->output->enable_profiler(TRUE);
	if (strtolower($filter) == 'started' && $whostarted == '')
		$whostarted = $this->meta['username'];

    $filtering = $this->_ready_filters($filter, $ordering, $dir, $whostarted);

    // get a thread count from the database
    $thread_count = $this->thread_dal->get_thread_count($filtering['filter']);

    // how many threads per page
    $display = $this->meta['threads_shown'] == false ? 50 : $this->meta['threads_shown'];

    // init the pagination library
    $this->pagination->initialize(array(
      'base_url' => '/p/',
      'total_rows' => $thread_count,
      'uri_segment' => '2',
      'num_links' => 4,
      'per_page' => $display,
      'suffix' => $filtering['url_suffix']
    ));

    // load up the header
    $this->load->view('shared/header');

    // end of threads
    $end = min(array($pagination + $display, $thread_count));

    $this->load->view('threads', array(
      'title' => $this->thread_dal->get_front_title(),
      'thread_result' => $this->thread_dal->get_threads($this->meta['user_id'], $pagination, $display, $filtering['filter'], $filtering['order']),
      'pagination' => $this->pagination->create_links() . '<span class="paging-text">' . ($pagination + 1) . ' - ' . $end . ' of ' . $thread_count . ' Threads</span>',
      'tab_links' => strlen($filter) > 0 ? '/f/'.$filter.'/' : '/o/',
      'tab_orders' => array(
        'started' => $ordering == 'started' && $dir == 'desc' ? 'asc' : 'desc',
        'latest' => $ordering == 'latest' && $dir == 'desc' ? 'asc' : 'desc',
        'posts' => $ordering == 'posts' && $dir == 'desc' ? 'asc' : 'desc',
        'startedby' => $whostarted
      ),
      'favorites' => explode(',', $this->thread_dal->get_favorites($this->meta['user_id'])),
      'hidden_threads' => explode(',', $this->thread_dal->get_hidden($this->meta['user_id']))
    ));

    $this->load->view('shared/footer');
  }

  function _ready_filters($filter, $ordering, $dir, $whostarted)
  {
    // switch through the filters
    switch(strtolower($filter))
    {
      case 'discussions':
        $sql = "WHERE threads.category = 1";
        break;
      case 'projects':
        $sql = "WHERE threads.category = 2";
        break;
      case 'advice':
        $sql = "WHERE threads.category = 3";
        break;
      case 'meaningless':
        $sql = "WHERE threads.category = 4";
        break;
      case 'meaningful':
        $sql = "WHERE threads.category != 4";
        break;
      case 'participated':
        $sql = "WHERE threads.thread_id IN (". $this->thread_dal->get_participated_threads($this->meta['user_id']) .")";
        break;
      case 'favorites':
        $sql = "WHERE threads.thread_id IN (". $this->thread_dal->get_favorites($this->meta['user_id']) .")";
        break;
      case 'hidden':
        $sql = "WHERE threads.thread_id IN (". $this->thread_dal->get_hidden($this->meta['user_id']) .")";
        break;
      case 'started':
		if ($whostarted!='') {
			$whostartedid = $this->user_dal->get_user_id_by_username($whostarted);
			if ($whostartedid===FALSE) $whostartedid = $this->meta['user_id'];
		}else{
			$whostartedid = $this->meta['user_id'];
		}
        $sql = "WHERE threads.thread_id IN (". $this->thread_dal->get_started_threads($whostartedid) .")";
        break;
      case 'all':
      default:
        $filter = $sql = '';
    }

	$sql .= $sql ? ' AND' : 'WHERE';

	if (strtolower($filter) != 'hidden')
	{
		$sql .= "  NOT EXISTS (SELECT hidden_threads.hidden_id FROM hidden_threads WHERE hidden_threads.user_id = ". $this->meta['user_id'] ." AND hidden_threads.thread_id = threads.thread_id) ";
		$sql .= ' AND threads.deleted = 0';
	} else {
		$sql .= ' threads.deleted = 0';
	}


    // make sure the direction is one or the other
    if (!in_array(strtolower($dir), array('desc', 'asc')))
      $dir = 'desc';

    switch(strtolower($ordering))
      {
      case 'started':
        $sql_order = "ORDER BY threads.created ". $dir;
        break;
      case 'latest':
        $sql_order = "ORDER BY response_created ". $dir;
        break;
      case 'posts':
        $sql_order = "ORDER BY response_count ". $dir;
        break;
      default:
        $sql_order = "ORDER BY response_created DESC";
        $ordering = $dir = '';
      }

    return array(
                 'filter' => $sql,
                 'order' => $sql_order,
                 'url_suffix' => (strlen($filter) > 0 ? '/'.$filter : '')
                 . (strlen($ordering) > 0 ? '/'.$ordering : '')
                 . (strlen($dir) > 0 ? '/'.$dir : '')
                 . (strlen($whostarted) > 0 ? '/'.$whostarted : '')
                 );
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */