<?php

?>

				<div id="main-title"><h3>Well aren't you Mr. Popular?</h3></div>
				
				<div id="pm-inbox">
					
					<div class="ctrl-bar">
						<div class="pagination">
						
						</div>
						<div class="new-message">
							<a href="/messages/send">NEW MESSAGE</a>
						</div>
					</div>
					
					<div id="pm-box-controls">
						<div class="left">
							<a href="/messages/inbox">Inbox (<?php echo $this->message_dal->unread_messages($this->session->userdata('user_id')); ?>)</a> |
							<a href="/messages/outbox">Sent Items</a>
						</div>
						<div class="right">
						
						</div>
					</div>
					
					<div class="message header">
						<div class="subject">Subject</div>
						<div class="sender">From</div>
						<div class="time">Received</div>
					</div>
					
<?php

foreach($messages->result() as $row) { 

$unread = $row->read === '0' ? ' unread' : '';

?>
					<div class="message<?php echo $unread; ?>">
						<div class="subject">
							<?php echo anchor('/message/'. $row->message_id, $row->subject); ?> 
						</div>
						<div class="sender">
							<?php echo $row->username; ?> 
						</div>
						<div class="time">
							<?php echo _format_pm_time($row->created); ?> 
						</div>
					</div>
					
					<div class="blue-bar"></div>
					
<?php } ?> 

					<div class="ctrl-bar">
						<div class="pagination">
						
						</div>
						<div class="new-message">
							<a href="/messages/send">NEW MESSAGE</a>
						</div>
					</div>
					
				</div>