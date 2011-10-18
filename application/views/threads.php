<?php

$logged_in = $this->sauth->is_logged_in();
$use_random_title = $this->session->userdata('random_titles') !== '0';

?>
				<div id="main-title"<?php echo $use_random_title ? ' class="changeling" title="'. $title->username .'"':''; ?>"><h3><?php echo ($use_random_title) ? $title->title_text : 'Threads'; ?></h3></div>

				<div id="thread-navigation" class="pagination top">
					<?php if ($logged_in) { ?>
					<a href="/newthread" id="post-thread">Post Thread</a>
					<?php } ?>
					<?php echo $pagination; ?>
				</div>


				<div class="thread" id="thread-headers">
					<div class="one">Thread Title &amp; Category</div>
					<div class="two"><a href="<?php echo $tab_links; ?>started/<?php echo $tab_orders['started']; ?>">Started By</a></div>
					<div class="three"><a href="<?php echo $tab_links; ?>latest/<?php echo $tab_orders['started']; ?>">Last Post</a></div>
					<div class="four"><a href="<?php echo $tab_links; ?>posts/<?php echo $tab_orders['started']; ?>">Posts</a></div>
				</div>

<?php

$display = $this->session->userdata('comments_shown') == false ? 50 : $this->session->userdata('comments_shown');

foreach($thread_result->result() as $row) {
	$alt = (!isset($alt) || $alt === false ? true : false);

	$link_text = '/thread/'.$row->thread_id.'/'.url_title($row->subject, 'dash', TRUE);

	$last_page = (ceil($row->response_count / $display) * $display - $display);

	$row->acq = (int) $row->acq;

	switch($row->acq)
	{
		case 1:
			$acq = ' buddy';
			break;
		case 2:
			$acq = ' enemy';
			break;
		default:
			$acq = '';
	}

	$nsfw = $row->nsfw === '1' ? ' nsfw' : '';
	$nsfw_tag = $row->nsfw === '1' ? ' <span class="naughty-tag">[NAUGHTY!]</span>' : '';

	if ($row->acq == 2)
	{
?>

				<div id="ignore-for-<?php echo $row->thread_id; ?>" class="ignore-container" onclick="$('#thread-<?php echo $row->thread_id; ?>').toggle();"></div>

<?php
	}

	$favorite = in_array($row->thread_id, $favorites) ? ' added' : '';

?>

				<div id="thread-<?php echo $row->thread_id; ?>" class="thread<?php echo $alt === false ? '' : ' alt'; echo $acq; echo $nsfw; ?>">
					<div class="one">
						<div class="subject"><span class="subject-text"><a href="<?php echo $link_text; ?>"><?php echo $row->subject; ?></a></span> <?php echo $nsfw_tag.' '. '<a href="'.$link_text.'/p/'.$last_page.'#bottom'; ?>" class='end-link'>#</a></div>
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
					<?php if ($logged_in) { ?><div class="five"><a class="favourite<?php echo $favorite; ?>" rel="<?php echo $row->thread_id; ?>"></a></div><?php } ?>
				</div>

				<div class="blueline">&nbsp;</div>
<?php } ?>
				<div class="pagination bottom">
					<?php if ($logged_in) { ?><a href="/newthread" id="post-thread">Post Thread</a><?php } ?>
					<?php echo $pagination; ?>
				</div>

<?php if ($logged_in) { ?>
				<script type="text/html" id="title-input">
					<input type="text" id="title-input" />
					<input type="submit" value="Save" id="save-title" />
					<input type="button" value="Cancel" id="cancel-title" />
					(36 chars max)
				</script>

				<script type="text/javascript">
					session_id = '<?php echo $this->session->userdata('session_id'); ?>';

					$('.favourite').bind('click', function(){
						button = $(this);

						if (!$(this).hasClass('added'))
						{
							$.get(
							'/ajax/favorite_thread/'+ $(this).attr('rel') +'/'+ session_id,
							function(data) {
								if (data == 1)
								{
									button.addClass('added');
								}
							}
							);
						}
						else
						{
							$.get(
							'/ajax/unfavorite_thread/'+ $(this).attr('rel') +'/'+ session_id,
							function(data) {
								if (data == 1)
								{
									button.removeClass('added');
								}
							}
							);
						}

						return;
					});
				</script>

<?php } ?>