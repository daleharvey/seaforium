
				<div id="main-title" title="<?php echo $title->username ?>"><h3><?php echo $title->title_text ?></h3></div>
				
				<div id="thread-navigation" class="pagination top">
          <a href="/newthread" id="post-thread">Post Thread</a>
					<?php echo $pagination; ?>
				</div>
	

			<div class="thread" id="thread-headers">
					<div class="one">Thread Title & Category</div>
					<div class="two">Started By</div>
					<div class="three">Last Post</div>
					<div class="four">Posts</div>
				</div>
			
<?php 

$display = $this->session->userdata('threads_shown') == false ? 50 : $this->session->userdata('threads_shown');

foreach($thread_result->result() as $row) { 
	$alt = (!isset($alt) || $alt === false ? true : false);
	
	$link_text = '/thread/'.$row->thread_id.'/'.url_title($row->subject, 'dash', TRUE);
	
	$last_page = (ceil($row->response_count / $display) * $display - $display);
?>

				<div class="thread<?php echo $alt === false ? '' : ' alt'; ?>">
					<div class="one">
						<div class="subject"><?php echo anchor($link_text, $row->subject).' '. anchor($link_text.'/p/'.$last_page.'#bottom', '#', array('class' => 'end-link')); ?></div>
						<div class="category"><?php echo $row->category ?></div>
					</div>
					<div class="two">
						<div class="username"><?php echo anchor('/user/'.url_title($row->author_name, 'dash', TRUE), $row->author_name); ?></div>
						<div class="time"><?php echo timespan(strtotime($row->created), time()) ?></div>
					</div>
					<div class="three">
						<div class="username"><?php echo anchor('/user/'.url_title($row->responder_name, 'dash', TRUE), $row->responder_name); ?></div>
						<div class="time"><?php echo timespan(strtotime($row->response_created), time()) ?></div>
					</div>
					<div class="four">
						<span><?php echo $row->response_count ?></span>
					</div> 
          <div class="five"><a class="favourite">&nbsp;</a></div>
				</div>
        <div class="blueline">&nbsp;</div>
<?php } ?>

				<div class="pagination bottom">
          <a href="/newthread" id="post-thread">Post Thread</a>
					<?php echo $pagination; ?>
				</div>

<script type="text/html" id="title-input">
   <input type="text" id="title-input" /> 
   <input type="submit" value="Save" id="save-title" />
   <input type="button" value="Cancel" id="cancel-title" />
   (36 chars max)
</script>
