<?php

$recipients = array(
	'name'	=> 'recipients',
	'id'	=> 'pm-recipients',
	'value' => set_value('recipients'),
	'size'	=> 30
);

$subject = array(
	'name'	=> 'subject',
	'id'	=> 'pm-subject',
	'value' => set_value('subject'),
	'maxlength'	=> 64,
	'size'	=> 30
);

$content = array(
	'name'	=> 'content',
	'id'	=> 'pm-content-input',
	'value' => set_value('content')
);

?>

				<div id="main-title"><h3>Well aren't you Mr. Popular?</h3></div>
				
				<div id="pm-send">
					
					<?php echo form_open('/messages/send'); ?>
						
						<div class="input text">
							<?php echo form_label('Recipients', $recipients['id']); ?>
							<?php echo form_input($recipients); ?>
						</div>
						
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