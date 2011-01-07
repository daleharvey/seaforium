
				<div id="main-title" title="<?php echo $title->username ?>"><?php echo $title->title_text ?></div>
				
				<div class="pagination top">
					<?php echo $pagination; ?>
				</div>
				
<?php 

$display = $this->session->userdata('threads_shown') == false ? 50 : $this->session->userdata('threads_shown');

foreach($thread_result->result() as $row) { 
	$alt = (!isset($alt) || $alt === false ? true : false);
	
	$link_text = '/thread/'.$row->thread_id.'/'.url_title($row->subject, 'dash', TRUE);
	
	$last_page = (ceil($row->response_count / $display) * $display - $display);
?>

				<div class="thread<?php echo $alt === false ? '' : ' alt'; ?>">
					<?php /* <div class="points">
						<span><a href="#">u</a></span>
						<span><a href="#">d</a></span>
					</div> */ ?> 
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
					<div class="five"><span>F</span></div>
				</div>
<?php } ?>

				<div class="pagination bottom">
					<?php echo $pagination; ?>
				</div>