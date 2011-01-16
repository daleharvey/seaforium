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
						
						<div class="inp">
							<?php echo form_label('Step 1: Write a thread title', $subject['id']); ?>
							<?php echo form_input($subject); ?>
						</div>
						
						<div class="inp">
							<?php echo form_label('Step 3: Type the content of your thread ', $content['id']); ?>
							<?php echo form_textarea($content); ?>
						</div>
						<?php echo form_submit('submit', 'Post Thread'); ?>
					<?php echo form_close(); ?>
					
				</div>