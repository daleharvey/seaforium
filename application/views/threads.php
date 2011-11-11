<?php
$logged_in = $this->sauth->is_logged_in();
$use_random_title = !($logged_in && $this->session->userdata('random_titles') !== '1');

if (!isset($tab_orders['startedby'])) {
  $tab_orders['startedby'] = '';
}

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
					<div class="two"><a href="<?php echo $tab_links; ?>started/<?php echo $tab_orders['started']; ?><?php if ($tab_orders['startedby']!='') {echo '/'.$tab_orders['startedby'];} ?>">Started By</a></div>
					<div class="three"><a href="<?php echo $tab_links; ?>latest/<?php echo $tab_orders['latest']; ?><?php if ($tab_orders['startedby']!='') {echo '/'.$tab_orders['startedby'];} ?>">Last Post</a></div>
					<div class="four"><a href="<?php echo $tab_links; ?>posts/<?php echo $tab_orders['posts']; ?><?php if ($tab_orders['startedby']!='') {echo '/'.$tab_orders['startedby'];} ?>">Posts</a></div>
				</div>

<?php

$display = $this->session->userdata('comments_shown') == false ? 50 : $this->session->userdata('comments_shown');

foreach($thread_result->result() as $row) {
	$alt = (!isset($alt) || $alt === false ? true : false);

	$link_text = '/thread/'.$row->thread_id.'/'.url_title($row->subject, 'dash', TRUE);

	$last_pagenumber = ceil($row->response_count / $display);
	$last_page = ($last_pagenumber * $display - $display);

	$printpages = '';
	if ($last_pagenumber > 1)
	{
		$tmplast_pagenumber = $last_pagenumber;
		if ($last_pagenumber > 8) $tmplast_pagenumber = 4;
		$printpages .= ' | Page:';
		for($p=1; $p<=$tmplast_pagenumber; ++$p)
		{
			$n_page = ($p * $display - $display);
			if ($p > 1) $printpages .= ',';
			$printpages .= ' <a href="'.$link_text.'/p/'.$n_page.'">'.$p.'</a>';
		}
		if ($last_pagenumber > 8)
		{
			$printpages .= ' <a href="'.$link_text.'/p/'.$last_page.'">...</a> <a href="'.$link_text.'/p/'.$last_page.'">'.$last_pagenumber.'</a>';
		}
	}

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

    if ($this->meta['hide_enemy_posts'] === '1')
      continue;

  ?>

				<div id="ignore-for-<?php echo $row->thread_id; ?>" class="ignore-container" onclick="$('#thread-<?php echo $row->thread_id; ?>').toggle();"></div>

<?php
	}

	$favorite = in_array($row->thread_id, $favorites) ? ' added' : '';
	$hidden = in_array($row->thread_id, $hidden_threads) ? ' added' : '';
        $my_thread = $row->user_id == $this->session->userdata('user_id');
?>

				<div id="thread-<?php echo $row->thread_id; ?>" class="thread<?php echo $alt === false ? '' : ' alt'; echo $acq; echo $nsfw; echo $my_thread ? ' mythread' : ''; ?>">
					<div class="one">
						<div class="subject"><span class="subject-text"><a href="<?php echo $link_text; ?>"><?php echo $row->subject; ?></a></span> <?php echo $nsfw_tag.' '. '<a href="'.$link_text.'/p/'.$last_page.'#bottom'; ?>" class='end-link'>#</a></div>
						<div class="category"><?php echo $row->category.$printpages ?></div>
					</div>
					<div class="two">
						<div class="username"><?php echo anchor('/user/'.url_title($row->author_name, 'dash', FALSE), $row->author_name); ?></div>
						<div class="time"><?php echo timespan(strtotime($row->created), time()) ?></div>
					</div>
					<div class="three">
						<div class="username"><?php echo anchor('/user/'.url_title($row->responder_name, 'dash', FALSE), $row->responder_name); ?></div>
						<div class="time"><?php echo timespan(strtotime($row->response_created), time()) ?></div>
					</div>
					<div class="four">
						<span><?php echo $row->response_count ?></span>
					</div>
					<?php if ($logged_in) { ?>
					<div class="five">
						<a class="favourite<?php echo $favorite; ?>" rel="<?php echo $row->thread_id; ?>"></a>
						<a class="hide-thread<?php echo $hidden; ?>" rel="<?php echo $row->thread_id; ?>"></a>
					</div>
					<?php } ?>
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
				</script>

<?php } ?>