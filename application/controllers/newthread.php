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
    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    $this->form_validation->set_rules('subject', 'Subject',
                                      'trim|required|xss_clean');
    $this->form_validation->set_rules('category[]', 'Category',
                                      'required|exact_length[1]|integer');
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

      $comment['thread_id'] = $this->thread_dal->new_thread($comment);
      $this->user_dal->update_thread_count($comment['user_id']);

      $this->thread_dal->new_comment($comment);
      redirect('/thread/'.$comment['thread_id'] . '/' .
               url_title($subject, 'dash', TRUE));
    }
    $this->load->view('shared/header');
    $this->load->view('newthread');
    $this->load->view('shared/footer');
  }
}

/* End of file newthread.php */
/* Location: ./application/controllers/newthread.php */