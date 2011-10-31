
				<div id="main-title"><h3><?php echo $user_data->username ?></h3></div>

				<div id="user">
					<div class="photostream">

					</div>

					<div class="personal_info_box">

					<?php

					if($this->uri->segment(2) == $this->session->userdata('username')){ ?>
						<div id="this-is-you" >
							<strong>This is You! <a href='preferences/'>Edit this page</a></strong>
							<br/>

						</div>
						<?php } ?>
						<div id="information" class="standard_profile_info_box">
						<h3><?php echo $user_data->username ?></h3>
						<span class="small_profile_caps">
							<span class="<?php echo strtolower($user_data->friendly_status); ?>"><?php echo $user_data->friendly_status; ?></span> 
							<span class="<?php echo strtolower(str_replace(' ', '_', $user_data->online_status)); ?>"><?php echo $user_data->online_status; ?>!</span>
							</span><br/>
							<?php if ($this->sauth->is_logged_in()) { ?>
							&rarr; <a href='/messages/send/<?php echo $this->uri->segment(2) ?>'>Send a message</a><br/>
							&rarr; <a href='/buddies/<?php echo $user_data->username; ?>'>Change buddy status</a><br/>
							<?php } ?>
							&rarr; <a href='/started/<?php echo $user_data->username; ?>'>View threads started</a>
						</div>
						<div id="stats" class="standard_profile_info_box">
							<h3>Stats</h3>
							<?php echo $user_data->username ?> is the <?php echo make_ordinal($user_data->id); ?> member of this place and has been here since <?php echo date('F jS Y', strtotime($user_data->created)); ?>.
							Since then, <?php echo $user_data->username ?> has posted <?php echo $user_data->threads_count ?> threads and <?php echo $user_data->comments_count ?> comments.
							That's a total of <?php echo $user_data->average_posts ?> posts per day. <?php echo $user_data->username . $user_data->last_login_text ?> Currently, <?php echo $user_data->username ?> is a friend of <?php echo $buddy_count ?> users, and is an enemy of <?php echo $enemy_count ?> users.

						</div>
						<div id="information-bio" class="standard_profile_info_box">
						<h3>Info</h3>
							<?php if (strlen($user_data->name) > 0) { ?><span class='small_profile_caps'>NAME: <?php echo $user_data->name; ?></span><br/><?php } ?>
							<?php if (strlen($user_data->location) > 0) { ?><span class='small_profile_caps'>LOC: <?php echo $user_data->location; ?></span><br/><?php } ?>
							<?php if (strlen($user_data->website_1) > 0) { ?><span class='small_profile_caps'>URL 1: </span><a href="<?php echo $user_data->website_1; ?>"><?php echo $user_data->website_1; ?></a><br/><?php } ?>
							<?php if (strlen($user_data->website_2) > 0) { ?><span class='small_profile_caps'>URL 1: </span><a href="<?php echo $user_data->website_2; ?>"><?php echo $user_data->website_2; ?></a><br/><?php } ?>
							<?php if (strlen($user_data->website_3) > 0) { ?><span class='small_profile_caps'>URL 1: </span><a href="<?php echo $user_data->website_3; ?>"><?php echo $user_data->website_3; ?></a><br/><?php } ?>
						</div>
						<div id="information-desc" class="standard_profile_info_box">
							<h3>Description</h3>
							<?php echo $user_data->about_blurb; ?>
						</div>

					</div>

					<div id="latest-posts">
					<? //echo $pagination; ?>

					<?php if(!$recent_posts): ?>
						<h1>This user has posted absolutely nothing on <?php echo $this->config->item('site_name'); ?>!</h1>
					<?php else:
						foreach($recent_posts as $post):
					?>
						<div class="post-container" id="post-<?php echo $post['comment_id']; ?>">
						<div class="post-block">POST</div>
						<h3 class="recent-thread-title"><a href='/thread/<?php echo $post['thread_id'] .'/'. url_title($post['subject']); ?>'><?php echo $post['subject'] ?></a></h3>
						<div class="recent-post-content">
							<?php echo $post['content']; ?>
						</div>

						<hr/>
						</div>

					<?php
						endforeach;
					 endif; ?>
					</div>

				</div>


  <script type="text/javascript" src="/js/thread.js"></script>
