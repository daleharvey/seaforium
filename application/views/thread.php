	
				<div id="thread">
					<div id="main-title"><?php echo $title ?></div>
					
					<div class="pagination top">
						<?php echo $pagination; ?>
					</div>
				
<?php

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
	
	$edit_source = ($row->user_id == $this->session->userdata('user_id') && strtotime($row->created) > time() - 3600) ? 'edit' : 'show source';
	
?>

					<div id="comment-<?php echo $row->comment_id; ?>" class="comment<?php echo $alt === false ? '' : ' alt'; ?>">
						<div class="cmd-bar">
 							<span><a href="#" class="view-source" onclick="thread.view_source(<?php echo $row->comment_id; ?>); return false;"><?php echo $edit_source; ?></a></span>
             <a class="quote">quote</a>
						</div>
						<div class="user-block">
							<div class="username"><?php echo anchor('/user/'.url_title($row->username, 'dash', TRUE), $row->username); ?></div>
							<div class="time"><?php echo timespan(strtotime($row->created), time()) ?></div>
						</div>
						<div class="content-block">
							<div class="content"><?php echo _ready_for_display($row->content); ?></div>
						</div>
  
						<div style="clear: right;"></div>
					</div>
<?php } ?>

					<div class="pagination bottom">
						<?php echo $pagination; ?>
					</div>

<?php if ($this->sauth->is_logged_in()) { ?>

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