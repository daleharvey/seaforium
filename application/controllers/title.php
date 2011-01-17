<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Title extends Controller
{
	function Title()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->model('thread_dal');
	}

  function edit() {

    $rules = 'required|min_length[1]|max_length[36]';    
    $this->form_validation->set_rules('title', 'Title', $rules);
    
    if ($this->form_validation->run() &&
        $this->sauth->is_logged_in()) { 
      
      $title = $this->input->post('title');
      $auth_id = $this->session->userdata('user_id');

      $sql = "INSERT INTO titles(title_text, author_id) VALUES(?, ?)";

      $this->db->query($sql, array(
			  $title,
			  $auth_id
      ));
      
      echo "saved";

    } else { 
      echo "error";
    }
  }
}

/* End of file title.php */
/* Location: ./application/controllers/title.php */