				<div id="main-title"><h3>Well aren't you Mr. Popular?</h3></div>
				
				<div id="pm-inbox">
					
					<div class="ctrl-bar">
						<div class="pagination">
						
						</div>
						<div class="new-message">
							<a href="/message/send">NEW MESSAGE</a>
						</div>
					</div>
					
					<div id="pm-box-controls">
						<div class="left">
							<a href="/messages/inbox">Inbox (<?php echo $this->message_dal->unread_messages($this->session->userdata('user_id')); ?>)</a> |
							<a href="/messages/outbox">Sent Items</a>
						</div>
						<div class="right">
              <a href="#" id="outbox-delete">Delete</a>
						</div>
					</div>
					
					<div class="message header">
            <div class="marker"><input type="checkbox" /></div>
						<div class="subject">Subject</div>
						<div class="sender">To</div>
						<div class="time">Received</div>
					</div>
					
          <form name="messages" id="message-form" method="post" action="/messages/action/outbox">
          
<?php

foreach($messages->result() as $row) { 
	
	$row->usernames = str_replace(',', ', ', $row->usernames);
	
	$username_display = strlen($row->usernames) > 16 ? substr($row->usernames, 0, 16) .'...' : $row->usernames;
	
	// to display how many recipients a PM was sent to.
	$recipient_count = substr_count($row->usernames, ',')+1;
	$multiple_recipients = $recipient_count > 1 ? '('. $recipient_count .')' : '';
?>
					<div class="message lineitem">
						<div class="marker">
							<input type="checkbox" value="<?php echo $row->message_id; ?>" name="message_ids[]" />
						</div>
						<div class="subject">
							<?php echo anchor('/message/'. $row->message_id, $row->subject); ?> 
						</div>
						<div class="sender">
							<?php echo $username_display .' '. $multiple_recipients; ?>
						</div>
						<div class="time">
							<?php echo _format_pm_time($row->created); ?> 
						</div>
					</div>
					
					<div class="blue-bar"></div>
					
<?php } ?> 

					<input type="hidden" name="action" id="message-form-action" />
					</form>

					<div class="ctrl-bar">
						<div class="pagination">
						
						</div>
						<div class="new-message">
							<a href="/message/send">NEW MESSAGE</a>
						</div>
					</div>
					
				</div>
        
        <script type="text/javascript" src="/js/messages.js"></script>