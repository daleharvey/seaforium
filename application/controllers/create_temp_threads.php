
<?php

class create_temp_threads extends Controller {

  function create_temp_threads()
  {
    parent::Controller();

    $this->load->helper(array('url', 'date', 'form', 'content_render',
                              'htmlpurifier'));
    $this->load->library(array('form_validation', 'pagination'));
    $this->load->model('thread_dal');

    // set all this so we dont have to continually call functions through session
    $this->meta = array(
      'user_id' => (int) $this->session->userdata('user_id'),
      'comments_shown' => $this->session->userdata('comments_shown') == false
        ? 50 : (int)$this->session->userdata('comments_shown')
    );
  }

  // if the just throw in /thread into the address bar
  // throw them home
  function index()
  {
  if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
	{
		@set_time_limit(300);
	}
  
	
  for($i=999; $i<100000; $i++) { 

	 $comment = array(
          'user_id' => $this->session->userdata('user_id'),
          'category' => 1,
          'subject' => "New test thread coming with lots of data " . $i,
          'content' => _process_post("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus placerat eleifend lectus sit amet rutrum. Pellentesque facilisis metus nibh. Pellentesque congue elit non est vehicula eget pulvinar tortor ultricies. Phasellus tincidunt feugiat tortor et elementum. Donec fringilla est nec nulla laoreet eget tincidunt purus rutrum. Mauris venenatis lobortis mattis. Phasellus et sem nec velit mollis suscipit. Nulla convallis lectus vitae est iaculis vel consequat lorem adipiscing.

Sed vel risus libero. Nullam ut tortor eu felis feugiat aliquam. Praesent et dolor orci. Quisque egestas enim urna. In iaculis, enim eleifend posuere venenatis, risus felis vehicula massa, a hendrerit lacus neque ac nisl. Fusce non diam massa, et eleifend felis. Proin non arcu ac orci venenatis placerat. Quisque tortor elit, imperdiet ut sodales vestibulum, euismod sagittis lectus. Suspendisse quis est massa, vel tincidunt felis. Fusce tristique adipiscing leo non pellentesque. Etiam hendrerit mi nec neque tincidunt ornare luctus ligula varius. Curabitur semper congue tortor a pharetra. Quisque in risus eu libero congue molestie eget eu leo. Cras fringilla tortor ut odio accumsan eu tristique tortor tincidunt."),
          'original_content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus placerat eleifend lectus sit amet rutrum. Pellentesque facilisis metus nibh. Pellentesque congue elit non est vehicula eget pulvinar tortor ultricies. Phasellus tincidunt feugiat tortor et elementum. Donec fringilla est nec nulla laoreet eget tincidunt purus rutrum. Mauris venenatis lobortis mattis. Phasellus et sem nec velit mollis suscipit. Nulla convallis lectus vitae est iaculis vel consequat lorem adipiscing.

Sed vel risus libero. Nullam ut tortor eu felis feugiat aliquam. Praesent et dolor orci. Quisque egestas enim urna. In iaculis, enim eleifend posuere venenatis, risus felis vehicula massa, a hendrerit lacus neque ac nisl. Fusce non diam massa, et eleifend felis. Proin non arcu ac orci venenatis placerat. Quisque tortor elit, imperdiet ut sodales vestibulum, euismod sagittis lectus. Suspendisse quis est massa, vel tincidunt felis. Fusce tristique adipiscing leo non pellentesque. Etiam hendrerit mi nec neque tincidunt ornare luctus ligula varius. Curabitur semper congue tortor a pharetra. Quisque in risus eu libero congue molestie eget eu leo. Cras fringilla tortor ut odio accumsan eu tristique tortor tincidunt."
        );

        $comment['thread_id'] = $this->thread_dal->new_thread($comment);

        $this->thread_dal->new_comment($comment);	
		
		echo "thread created\<br/>";
	}
	//die("All done");
  }
 } 
 ?>

