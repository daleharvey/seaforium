<?php

class Newthread extends Controller {

	function Newthread()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('thread_dal');
		
		if (!$this->sauth->is_logged_in())
			redirect('/');
	}
	
	function index()
	{
		
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('category[]', 'Category', 'required|exact_length[1]|integer');
		$this->form_validation->set_rules('content', 'Content', 'trim|required|xss_clean');
		
		if ($this->form_validation->run())
		{	
			
			$subject = $this->form_validation->set_value('subject');
			
			$category = $this->form_validation->set_value('category[]');
			
			$comment = array(
				'user_id' => $this->session->userdata('user_id'),
				'category' => (int)$category[0],
				'subject' => $this->form_validation->set_value('subject'),
				'content' => $this->form_validation->set_value('content')
			);
			
			$comment['thread_id'] = $this->thread_dal->new_thread($comment);
			
			$this->thread_dal->new_comment($comment);
			
			redirect('/thread/'.$thread_id.'/'.url_title($subject, 'dash', TRUE));
		}
		
		$this->load->view('shared/header');
		$this->load->view('newthread');
		$this->load->view('shared/footer');
	}
}

/* End of file newthread.php */
/* Location: ./application/controllers/newthread.php */