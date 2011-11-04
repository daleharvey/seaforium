			
			<div id="main-title"><h3><?php echo $message->subject; ?></h3></div>
			
			<div id="pm-message">
				
				<div class="ctrl-bar">
					<div class="pagination">
						<a href="/messages/inbox" class="nobox">Go back to Inbox</a>
					</div>
					<div class="new-message">
						<a href="/message/reply/<?php echo $message->message_id; ?>">REPLY</a>
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
				
				<div id="pm-message-meta">
					<span><span>Subject:</span> <?php echo $message->subject; ?></span> | 
					<span><span>From:</span> <a href="/user/<?php echo $message->username; ?>"><?php echo $message->username; ?></a></span> |
					<span><span>Received:</span> <?php echo _format_pm_time($message->created); ?></span>
				</div>
				
				<div class="content">
					<?php echo nl2br($message->content); ?>
				</div>
				
				<div class="ctrl-bar">
					<div class="pagination">
						<a href="/messages/inbox" class="nobox">Go back to Inbox</a>
					</div>
					<div class="new-message">
						<a href="/message/reply/<?php echo $message->message_id; ?>">REPLY</a>
					</div>
				</div>
			</div>