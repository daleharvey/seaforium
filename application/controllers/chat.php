<?php

class Chat extends Controller
{
  function __construct()
  {
    parent::__construct();
    
    $this->load->helper(array('url'));
  }

  /**
   * Display everyones beloved tinychat page
   *
   * @return void
   */
  function index()
  {
    $this->load->view('shared/header');
    
    $this->load->view('chat');
    
    $this->load->view('shared/footer');
  }
}

/* End of file chat.php */
/* Location: ./application/controllers/chat.php */
