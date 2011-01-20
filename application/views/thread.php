	
				<div id="thread">
					<div id="main-title"><h3><?php echo $info['title'] ?></h3></div>
					
					<div class="pagination top">
						<?php echo $pagination; ?>
					</div>
				
<?php

$i = 0;

// loop through and print out all the comments
foreach($comment_result->result() as $row) { 

	// if the comment has been deleted,
	// go ahead and just put the deleted thing and start the loop over
	if ($row->deleted == '1')
	{
?>

					<div class="comment deleted">
						comment by <?php echo $row->username ?> deleted
					</div>
<?php
		continue;
	}
	
	// alternating comment colors!!!
	$alt = (!isset($alt) || $alt === false ? true : false);
	
	$my_thread = $row->user_id == $this->session->userdata('user_id');
	
	$edit_source = ($my_thread && strtotime($row->created) > time() - 3600) ? 'edit' : 'View Source';
	
	$url_safe_username = url_title($row->username, 'dash', TRUE);
?>

					<div id="comment-<?php echo $row->comment_id; ?>" class="comment<?php echo $alt === false ? '' : ' alt'; ?>">
						<div class="cmd-bar">
 							<span><a class="view-source" onclick="thread.view_source(<?php echo $row->comment_id; ?>); return false;"><?php echo $edit_source; ?></a></span>
             <a class="quote">Quote</a>
						</div>
						<div class="user-block">
							<div class="username"><?php echo anchor('/user/'. $url_safe_username, $row->username); ?></div>
							<div class="time"><?php echo timespan(strtotime($row->created), time()) ?></div>
							
							<div class="user-information" style="background: url(img/noavatar.gif);">
								<ul>
									<li><a href="/buddies/<?php echo $url_safe_username; ?>">BUDDY? ENEMY?</a></li>
									<li><a href="/messages/send/<?php echo $url_safe_username; ?>">SEND A MESSAGE</a></li>
								</ul>
							</div>
							
							<?php if ($my_thread && $i === 0 && $starting === 0) { 
							
								$session_id = $this->session->userdata('session_id');
								
								$set_nsfw_status = $info['nsfw'] === '1' ? 0 : 1;
								$set_closed_status = $info['closed'] === '1' ? 0 : 1;
								
								$nsfw_text = $info['nsfw'] === '1' ? 'Unmark Naughty' : 'Mark Naughty';
								$closed_text = $info['closed'] === '1' ? 'Open Thread' : 'Close Thread';
							
							?>
							<div id="thread-control">
								<p>THREAD ADMIN</p>
								<ul>
									<li id="control-nsfw">&middot; <span><?php echo $nsfw_text; ?></span></li>
									<li id="control-closed">&middot; <span><?php echo $closed_text; ?></span></li>
								</ul>
							</div>
							
							<script type="text/javascript">
								$('#control-nsfw span').bind('click', function(){
									thread.set_status(<?php echo $row->thread_id; ?>, 'nsfw', <?php echo $set_nsfw_status; ?>, '<?php echo $session_id; ?>')
								});
								$('#control-closed span').bind('click', function(){
									thread.set_status(<?php echo $row->thread_id; ?>, 'closed', <?php echo $set_closed_status; ?>, '<?php echo $session_id; ?>');
								});
							</script>
							<?php } ?>
							
						</div>
						<div class="content-block">
							<div class="content"><?php echo _ready_for_display($row->content); ?></div>
						</div>
  
						<div style="clear: both;"></div>
					</div>
<?php ++$i; } ?>

					<div class="pagination bottom">
						<?php echo $pagination; ?>
					</div>

<?php if ($this->sauth->is_logged_in() && $info['closed'] === '0') { ?>

<?php

// and now the reply form
$content = array(
	'name'	=> 'content',
	'id'	=> 'thread-content-input',
	'value' => set_value('content')
);

?>

					<?php echo form_open(uri_string()); ?> 
					
						<div class="input textarea">
							<?php echo form_textarea($content); ?> 
						</div>
					
						<?php echo form_submit('submit', 'Submit'); ?> 
					<?php echo form_close(); ?> 

<?php } ?> 
					<script type="text/javascript" src="/js/thread.js"></script>
					<script type="text/javascript">
						thread_id = <?php echo $thread_id; ?> 
						total_comments = <?php echo $total_comments; ?> 
						setInterval("thread_notifier()",10000);
					</script>
					
				</div>