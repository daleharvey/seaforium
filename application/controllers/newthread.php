<?php

class Newthread extends Controller {

	function Newthread()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}
	
	function index()
	{
		
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('content', 'Content', 'trim|required|xss_clean');
		
		if ($this->form_validation->run())
		{	
			
			$subject = $this->form_validation->set_value('subject');
			
			// insert a new thread into the database
			// the variables are done one at a time to take advantage of set()'s 3rd argument
			// set() by default puts quotes around the 2nd argument but since
			// its a native mySQL function, we dont want it quoted
			$this->db->set('user_id', $this->session->userdata('user_id'));
			$this->db->set('subject', $subject);
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('threads');
			
			// grab the new thread id
			$thread_id = $this->db->insert_id();
			
			// insert a new comment into the database
			$this->db->set('thread_id', $thread_id);
			$this->db->set('user_id', $this->session->userdata('user_id'));
			$this->db->set('content', $this->form_validation->set_value('content'));
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('comments');
			
			// now grab the new comment id
			$comment_id = $this->db->insert_id();
			
			// update the new thread we made with the latest comment id
			$this->db->where('thread_id', $thread_id);
			$this->db->update('threads', array(
				'last_comment_id' => $comment_id
			));
			
			redirect('/thread/'.$thread_id.'/'.url_title($subject, 'dash', TRUE));
		}
		
		$this->load->view('shared/header');
		$this->load->view('newthread');
		$this->load->view('shared/footer');
	}
}

/* End of file newthread.php */
/* Location: ./application/controllers/newthread.php */