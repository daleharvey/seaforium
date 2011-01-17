<?php

?>

				<div id="main-title">MOTHERFUCKING INBOX</div>
				
				<div id="pm-inbox">
					
<?php

foreach($messages->result() as $row) { 

?>
					<div class="message">
						<div class="subject">
							<?php echo anchor('/message/'. $row->message_id, $row->subject); ?> 
						</div>
						<div class="sender">
							<?php echo $row->username; ?> 
						</div>
						<div class="time">
							<?php echo $row->created; ?> 
						</div>
					</div>
<?php } ?> 

				</div>