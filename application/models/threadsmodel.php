<?php

class Threadsmodel extends Model
{
  var $args, $meta, $thread_count;

  function __construct()
  {
    parent::__construct();
  }

  /**
   * Get some threads from the database
   *
   * @param	int
   * @param	int
   * @return	object
   */
  function get_threads($in = '')
  {
    $searching = !!$in;

    $this->db->select('
      SQL_CALC_FOUND_ROWS
      threads.subject,
      threads.created,
      threads.closed,
      threads.nsfw,
      threads.thread_id,
      threads.user_id,
      categories.name AS category,
      authors.username AS author_name,
      responders.username AS responder_name,
      responses.created AS response_created,
      IFNULL(acquaintances.type, 0) AS acq,
      (
        SELECT count(comments.comment_id)
        FROM comments
        WHERE comments.thread_id = threads.thread_id
      ) AS response_count
      ', FALSE);

    $this->db->from('threads');

    $this->db->join('comments AS responses',
                    'responses.comment_id = threads.last_comment_id');

    $this->db->join('users AS authors',
                    'threads.user_id = authors.id');

    $this->db->join('users AS responders',
                    'responses.user_id = responders.id');

    $this->db->join('categories',
                    'threads.category = categories.category_id', 'left');

    $this->db->join('acquaintances',
                    'acquaintances.acq_user_id = authors.id AND acquaintances.user_id = '. $this->meta['user_id']
                     , 'left');

    if (isset($this->args->search_terms)) {
      $this->db->like('threads.subject', $this->args->search_terms);
    }
    else
    {
      if (!$this->sauth->is_logged_in() && $this->args->filter === "") {
        $this->args->filter = "meaningful";
      }

      switch(strtolower($this->args->filter))
      {
        case 'discussions':
          $this->db->where('threads.category', 1);
          break;
        case 'projects':
          $this->db->where('threads.category', 2);
          break;
        case 'advice':
          $this->db->where('threads.category', 3);
          break;
        case 'meaningless':
          $this->db->where('threads.category', 4);
          break;
        case 'meaningful':
          $this->db->where('threads.category !=', 4);
          break;
        case 'participated':
          $this->db->where('threads.thread_id IN (SELECT DISTINCT comments.thread_id FROM comments, threads WHERE comments.user_id = '. $this->meta['user_id']
            .' AND comments.thread_id = threads.thread_id AND threads.deleted = 0) AND NOT EXISTS (SELECT hidden_threads.hidden_id FROM hidden_threads WHERE hidden_threads.user_id = '. $this->meta['user_id']
            .' AND hidden_threads.thread_id = threads.thread_id)');
          break;
        case 'favorites':
          $this->db->where('threads.thread_id IN (SELECT DISTINCT threads.thread_id FROM favorites, threads WHERE favorites.user_id = '. $this->meta['user_id']
            .' AND favorites.thread_id = threads.thread_id AND threads.deleted = 0)');
          break;
        case 'hidden':
          $this->db->where('threads.thread_id IN (SELECT hidden_threads.thread_id FROM hidden_threads,threads WHERE hidden_threads.user_id = '. $this->meta['user_id']
            .' AND hidden_threads.thread_id = threads.thread_id AND threads.deleted = 0)');
          break;
        case 'started':
          if ($this->args->whostarted != '') {
            $whostartedid = $this->user_dal->get_user_id_by_username($this->args->whostarted);
            if ($whostartedid === FALSE)
              $whostartedid = $this->meta['user_id'];
          }else{
            $whostartedid = $this->meta['user_id'];
          }
          $this->db->where('threads.thread_id IN (SELECT DISTINCT thread_id FROM threads WHERE user_id = '. $whostartedid .' AND deleted = 0)');
          break;
      }
    }

    // dont show hidden threads
    // unless thats what we're trying to do
    if ($this->args->filter != 'hidden')
      $this->db->where('NOT EXISTS (SELECT hidden_threads.hidden_id FROM hidden_threads WHERE hidden_threads.user_id = '. $this->meta['user_id']
        .' AND hidden_threads.thread_id = threads.thread_id)', null, false);

      // dont want to show any deleted threads
    $this->db->where('threads.deleted', 0);

    if (!$searching)
    {
      // get ordering direction
      if ($this->args->dir != 'desc' && $this->args->dir != 'asc')
        $this->args->dir = 'desc';

      // order the threads
      if ($this->args->ordering == 'started') {
        $this->db->order_by('threads.created', $this->args->dir);
      }
      elseif ($this->args->ordering == 'latest') {
        $this->db->order_by('response_created', $this->args->dir);
      }
      elseif ($this->args->ordering == 'posts') {
        $this->db->order_by('response_count', $this->args->dir);
      }
      else {
        $this->db->order_by('response_created', 'DESC');
      }

      // apply some limits to this thing
      $this->db->limit($this->args->pagination, $this->meta['threads_shown']);
    }

    //echo $this->db->_compile_select();

    // get the results
    $this->thread_results = $this->db->get();

    // format out a url_suffix to use with pagination
    $this->url_suffix =
      (strlen($this->args->filter) > 0 ? '/'. $this->args->filter : '').
      (strlen($this->args->ordering) > 0 ? '/'. $this->args->ordering : '').
      (strlen($this->args->dir) > 0 ? '/'. $this->args->dir : '').
      (strlen($this->args->whostarted) > 0 ? '/'. $this->args->whostarted : '');

    // get a count of all threads
    $this->thread_count = (int)$this->db->query('SELECT FOUND_ROWS() AS thread_count')->row()->thread_count;
  }
}