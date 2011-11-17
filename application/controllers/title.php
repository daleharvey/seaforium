<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Title extends Controller
{
  function Title()
  {
    parent::__construct();

    $this->load->library('form_validation');
    $this->load->model('thread_dal');
  }

  function index() {
    $query = $this->db->query("SELECT users.username, titles.title_text FROM ".
                              "titles LEFT JOIN users ON titles.author_id = ".
                              "users.id");

    foreach ($query->result() as $row) {
      echo $row->username . " - " . $row->title_text . "<br />";
    }
  }

  function edit() {

    $thread_id = $this->input->post('thread_id');

    if($thread_id) {
      $rules = 'required|min_length[1]|max_length[64]|xss_clean';
      $this->form_validation->set_rules('title', 'Title', $rules);

      if ($this->form_validation->run() &&
          $this->sauth->is_logged_in()) {

        $title = $this->input->post('title');
        $user_id = $this->session->userdata('user_id');

        $this->load->model('thread_dal');

        echo $this->thread_dal->update_subject($thread_id, $title, $user_id)
          ? "saved" : "error";

      } else {
        echo "error";
      }
    } else {

      $rules = 'required|min_length[1]|max_length[36]|xss_clean';
      $this->form_validation->set_rules('title', 'Title', $rules);

      if ($this->form_validation->run() &&
          $this->sauth->is_logged_in()) {

        $title = $this->input->post('title');
        $auth_id = $this->session->userdata('user_id');

        $sql = "INSERT INTO titles(title_text, author_id) VALUES(?, ?)";

        $this->db->query($sql, array($title, $auth_id));

        echo "saved";

      } else {
        echo "error";
      }
    }
  }
}

/* End of file title.php */
/* Location: ./application/controllers/title.php */