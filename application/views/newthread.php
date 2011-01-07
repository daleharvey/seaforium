<?php

$subject = array(
	'name'	=> 'subject',
	'id'	=> 'subject',
	'value' => set_value('subject'),
	'maxlength'	=> 64,
	'size'	=> 30
);

$content = array(
	'name'	=> 'content',
	'id'	=> 'thread-content-input',
	'value' => set_value('content')
);

?>

				<div id="main-title">Submit a new thread!</div>
				
				<div id="new-thread">
					
					<?php echo form_open('/newthread'); ?>
						
						<div class="input text">
							<?php echo form_label('Subject', $subject['id']); ?>
							<?php echo form_input($subject); ?>
						</div>
						
						<div class="input textarea">
							<?php echo form_label('Body', $content['id']); ?>
							<?php echo form_textarea($content); ?>
						</div>
						
						<?php echo form_submit('submit', 'Submit'); ?>
					<?php echo form_close(); ?>
					
				</div>