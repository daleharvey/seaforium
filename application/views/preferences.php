<?php

$elements_shown_options = array(
                  '25'	=> '25',
                  '50'	=> '50',
                  '100'	=> '100'
                );

$content = array(
	'name'	=> 'content',
	'id'	=> 'thread-content-input',
	'value' => set_value('content')
);

?>

				<div id="main-title">Your Preferences</div>
				
				<div id="preferences">
					
					<?php echo form_open('/preferences'); ?>
						
						<div class="input text">
							<?php echo form_label('Threads Shown', 'threads_shown'); ?>
							<?php echo form_dropdown('threads_shown', $elements_shown_options, $this->session->userdata('threads_shown').''); ?>
						</div>
						
						<div class="input text">
							<?php echo form_label('Comments Shown', 'comments_shown'); ?>
							<?php echo form_dropdown('comments_shown', $elements_shown_options, $this->session->userdata('comments_shown').''); ?>
						</div>
						
						<?php echo form_submit('submit', 'Submit'); ?>
					<?php echo form_close(); ?>
					
				</div>