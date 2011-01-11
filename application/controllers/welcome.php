<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		
		$this->load->helper(array('date', 'url'));
		$this->load->library('pagination');
	}
	
	function index($pagination = 0)
	{
		$data['title'] = $this->db->query('
			SELECT
				titles.title_text,
				users.username
			FROM titles
			LEFT JOIN users
				ON titles.author_id = users.id
			ORDER BY titles.title_id DESC
			LIMIT 1
		')->row();

    if (count($data["title"]) == 0) { 
      $data["title"] = (object) array("title_text" => "Change Me, Please", 
                                      "username" => "anon");
    }
      
		$row_count = $this->db->query('SELECT count(threads.thread_id) AS max_rows FROM threads')->row();
		
		$display = $this->session->userdata('threads_shown') == false ? 50 : $this->session->userdata('threads_shown');
		
		$data['thread_result'] = $this->db->query('
			SELECT
				threads.subject,
				threads.created,
				threads.thread_id,
				threads.category,
				# authors.id AS author_id,
				authors.username AS author_name,
				# responders.id AS responder_id,
				responders.username AS responder_name,
				responses.created AS response_created,
				(
					SELECT 
						count(comments.comment_id) 
					FROM comments 
					WHERE comments.thread_id = threads.thread_id
				) AS response_count
			FROM threads
			JOIN comments AS responses
				ON responses.comment_id = threads.last_comment_id
			JOIN users AS authors
				ON threads.user_id = authors.id
			JOIN users AS responders
				ON responses.user_id = responders.id
			ORDER BY response_created DESC
			LIMIT '. $pagination .', '. $display .'
		');
		
		$this->pagination->initialize(array(
			'base_url' => '/p/',
			'total_rows' => $row_count->max_rows,
			'uri_segment' => '2',
			'per_page' => $display
		)); 
		
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->view('shared/header');
		$this->load->view('threads', $data);
		$this->load->view('shared/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */