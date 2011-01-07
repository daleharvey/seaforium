<?php

class Thread extends Controller {

	function Thread()
	{
		parent::Controller();

		$this->load->helper(array('url', 'date', 'form', 'xml', 'content_render'));
		$this->load->library(array('form_validation', 'pagination'));
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
		if (!is_numeric($thread_id))
			redirect('/');
		
		// grabbing the thread information
		$query = $this->db->get_where('threads', array('thread_id' => $thread_id));
		
		// does it exist?
		if ($query->result_id->num_rows === 0)
			redirect('/');
		
		// alright we're clear, make note of the subject for the view
		$data['title'] = $query->row()->subject;
		
		// we're going to go ahead and do the form processing for the reply now
		// if they're submitting data, we're going to refresh the page anyways
		// so theres no point in running the query below the form validation
		$this->form_validation->set_rules('content', 'Content', 'trim|required|xss_clean');
		
		if ($this->form_validation->run())
		{
			
			$content = _ready_for_save($this->form_validation->set_value('content'));
			
			// insert a new comment into the database
			// the variables are done one at a time to take advantage of set()'s 3rd argument
			// set() by default puts quotes around the 2nd argument but since
			// its a native mySQL function, we dont want it quoted
			$this->db->set('thread_id', $thread_id);
			$this->db->set('user_id', $this->session->userdata('user_id'));
			$this->db->set('content', $content);
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('comments');
			
			$comment_id = $this->db->insert_id();
			
			// update the new thread we made with the latest comment id
			$this->db->where('thread_id', $thread_id);
			$this->db->update('threads', array(
				'last_comment_id' => $comment_id
			));
			
			redirect(uri_string());
		}
		
		$row_count = $this->db->query('SELECT count(comments.comment_id) AS max_rows FROM comments WHERE comments.thread_id = '. $thread_id)->row();
		
		$display = $this->session->userdata('comments_shown') == false ? 50 : $this->session->userdata('comments_shown');
		
		$pseg = 0;
		$base_url = '';
		$pagination = 0;
		
		for($i=1; $i<=count($this->uri->segments); ++$i)
		{
			$base_url .= '/'. $this->uri->segments[$i];
			
			if ($this->uri->segments[$i] == 'p')
			{
				if (isset($this->uri->segments[$i+1]) && is_numeric($this->uri->segments[$i+1]))
				{
					$pseg = $i+1;
					$pagination = $this->uri->segments[$i+1];
					
					break;
				}
			}
		}
		
		if ($pseg === 0) $base_url .= '/p';
		
		$data['comment_result'] = $this->db->query('
			SELECT
				comments.comment_id,
				comments.content,
				comments.created,
				comments.deleted,
				users.username
			FROM comments
			LEFT JOIN users
				ON comments.user_id = users.id
			WHERE comments.thread_id = '.$thread_id.'
			ORDER BY comments.created
			LIMIT '. $pagination .', '. $display .'
		');
		
		$this->pagination->initialize(array(
			'base_url' => $base_url,
			'total_rows' => $row_count->max_rows,
			'uri_segment' => $pseg,
			'per_page' => $display
		)); 
		
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->helper('content_render');
		
		$this->load->view('shared/header');
		$this->load->view('thread', $data);
		$this->load->view('shared/footer');
	}
	
	function _ready_content($content)
	{
		
		$content = nl2br($content);
		
		return $content;
	}
}

/* End of file thread.php */
/* Location: ./application/controllers/thread.php */