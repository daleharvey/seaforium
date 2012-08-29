<?php

class Newthread extends Controller {

  function Newthread()
  {
    parent::Controller();

    $this->load->helper(array('form', 'url', 'htmlpurifier', 'content_render'));
    $this->load->library('form_validation');
    $this->load->model('thread_dal');

    if (!$this->sauth->is_logged_in())
      redirect('/');
  }

  function index()
  {
    $ajax = isset($_POST['ajax']);

    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    $this->form_validation->set_rules('subject', 'Subject',
                                      'trim|required|xss_clean');
    $this->form_validation->set_rules('category[]', 'Category',
                                      'required|exact_length[1]|integer');
    $this->form_validation->set_rules('content', 'Content', 'trim|required');
    
    $this->form_validation->set_rules('content', 'Content', 'trim|required');
 
    if ($this->form_validation->run()) {

      $subject = $this->form_validation->set_value('subject');
      $content = $this->form_validation->set_value('content');
      $category = $this->form_validation->set_value('category[]');
      
      $comment = array(
        'user_id' => $this->session->userdata('user_id'),
        'category' => (int)$category[0],
        'subject' => $subject,
        'content' => _process_post($content),
        'original_content' => $content
      );
      /*
      !$this->thread_dal->are_you_posting_too_fast($this->session->userdata('user_id') ) ||
      */
      if( $this->thread_dal->has_thread_just_been_posted($subject, $this->session->userdata('user_id')) || $this->thread_dal->are_you_posting_too_fast($this->session->userdata('user_id') == TRUE ))
      {
	   	return send_json($this->output, 400, array('error' => true,
                                                   'reason' =>  "<div class=\"error\">Your are posting too fast or this thread has just been posted.</div>"));   
      }
      $comment['thread_id'] = $this->thread_dal->new_thread($comment);
      $this->user_dal->update_thread_count($comment['user_id']);

      $this->thread_dal->new_comment($comment);
      $url = '/thread/'.$comment['thread_id'] . '/' . url_title($subject, 'dash', TRUE);

      if ($ajax) {
        return send_json($this->output, 201, array('ok' => true, 'url' =>  $url));
      } else {
        redirect($url);
      }
    } else {
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ajax) {
        return send_json($this->output, 400, array('error' => true,
                                                   'reason' =>  validation_errors()));
      }
    }
    $this->load->view('shared/header');
    $this->load->view('newthread');
    $this->load->view('shared/footer');
  }
  
}

/* End of file newthread.php */
/* Location: ./application/controllers/newthread.php */