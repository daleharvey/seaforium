<?php

$recip_input = set_value('recipients');

$recipients = array(
	'name'	=> 'recipients',
	'id'	=> 'pm-recipients',
  'value' => set_value('recipients', $message['recipients']),
	//'value' => isset($recip_input) && strlen($recip_input) > 0 ? $recip_input : $to,
	'size'	=> 30
);

$save_sent = array(
	'name'		=> 'save_sent',
	'id'		=> 'save-sent',
	'value' 	=> 'save',
	'checked'	=> TRUE
);

$read_receipt = array(
	'name'		=> 'read_receipt',
	'id'		=> 'read-receipt',
	'value' 	=> 'receipt',
	'checked'	=> FALSE
);

$subject = array(
	'name'	=> 'subject',
	'id'	=> 'pm-subject',
	'value' => set_value('subject', $message['subject']),
	'maxlength'	=> 64,
	'size'	=> 30
);

$content = array(
	'name'	=> 'content',
	'id'	=> 'pm-content-input',
	'value' => set_value('content', $message['content'])
);

$err_display = '';

if (isset($errors) && strlen($errors) > 0)
{
	$err_display = '<div id="errors"><h4>Woops, looks like you suck at the internet.</h4><ul>'. $errors .'</ul></div>';
}

?>

				<div id="main-title"><h3>Send a message</h3></div>
				
				<div id="pm-send">
					
					<p>Send a message to another member. Remember, if you don't recieve a reply it may not be that you're being ignored, most people don't check their message very often.</p>
					
					<?php echo $err_display; ?>
					
					<div class="dotted-bar"></div>
					
					<?php echo form_open('/message/send'); ?>
						
						<div class="inp">
							<?php echo form_label('To: (comma delimited usernames)', $recipients['id']); ?>
							<?php echo form_input($recipients); ?>
              
              <div id="recipient-buddies">
                <?php /*foreach($buddies as $buddy){ ?>
                <a rel="<?php echo $buddy->username; ?>">+<?php echo $buddy->username; ?></a>,
                <?php }*/ ?>
              </div>
						</div>
						<div class="inp">
							<div class="cbx">
								<?php echo form_label('Save copy in sent folder?', $save_sent['id']); ?>
								<?php echo form_checkbox($save_sent); ?>
							</div>
							<div class="cbx">
								<?php echo form_label('Read receipt?', $read_receipt['id']); ?>
								<?php echo form_checkbox($read_receipt); ?>
							</div>
						</div>
						
						<div class="inp">
							<?php echo form_label('Subject:', $subject['id']); ?>
							<?php echo form_input($subject); ?>
						</div>
						
						<div class="inp">
							<?php echo form_label('Your message:', $content['id']); ?>
							<?php echo form_textarea($content); ?>
						</div>
						
						<?php echo form_submit('submit', 'Send message'); ?>
					<?php echo form_close(); ?>
					
				</div>