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
							Mark <a href="#" id="inbox-mark-read">Read</a>
							<a href="#" id="inbox-mark-unread">Unread</a>
							|
							<a href="#" id="inbox-delete">Delete</a>
						</div>
					</div>

					<div class="message header">
						<div class="marker"><input type="checkbox" /></div>
						<div class="subject">Subject</div>
						<div class="sender">From</div>
						<div class="time">Received</div>
					</div>

					<form name="messages" id="message-form" method="post" action="/messages/action/inbox">

<?php

$i = 0;

foreach($messages->result() as $row) {

$buddy = $row->buddy_type === '1';
$unread = $row->read === '0' ? ' unread' : '';

?>
					<div class="message<?php echo $unread; ?> lineitem <?php echo $buddy ? "buddy " : ""; echo (++$i % 2 == 0) ? "odd" : ""; ?>">
						<div class="marker">
							<input type="checkbox" value="<?php echo $row->message_id; ?>" name="message_ids[]" />
						</div>
						<div class="subject">
							<?php echo anchor('/message/'. $row->message_id, $row->subject); ?>
						</div>
						<div class="sender">
							<a href="/user/<?php echo $row->username; ?>"><?php echo $row->username; ?></a>
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