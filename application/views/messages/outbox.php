<?php

?>

				<div id="main-title"><h3>Well aren't you Mr. Popular?</h3></div>
				
				<div id="pm-inbox">
					
<?php

foreach($messages->result() as $row) { 
	
	$row->usernames = str_replace(',', ', ', $row->usernames);
	
	$username_display = strlen($row->usernames) > 16 ? substr($row->usernames, 0, 16) .'...' : $row->usernames;
	
	// to display how many recipients a PM was sent to.
	$recipient_count = substr_count($row->usernames, ',')+1;
	$multiple_recipients = $recipient_count > 1 ? '('. $recipient_count .')' : '';
?>
					<div class="message">
						<div class="subject">
							<?php echo anchor('/message/'. $row->message_id, $row->subject); ?> 
						</div>
						<div class="sender">
							<?php echo $username_display .' '. $multiple_recipients; ?>
						</div>
						<div class="time">
							<?php echo $row->created; ?> 
						</div>
					</div>
<?php } ?> 

				</div>