
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
						<span class='small_profile_caps'>BUDDY NOT ONLINE!</span><br/>
							<?php if ($this->sauth->is_logged_in()) { ?>
							&rarr; <a href='/messages/send/<?php echo $this->uri->segment(2) ?>'>Send a message</a><br/>
							&rarr; <a href='#'>Change buddy status</a><br/>
							<?php } ?>
							&rarr; <a href='/f/started'>View threads started</a><br/>
							&rarr; x people invited by <?php echo $user_data->username ?><br/>
						
						</div>
						<div id="stats" class="standard_profile_info_box">
							<h3>Stats</h3>
							<?php echo $user_data->username ?> is the <?php echo make_ordinal($user_data->id); ?> member of this place and has been here since <?php echo date('F jS Y', strtotime($user_data->created)); ?>.
							Since then, <?php echo $user_data->username ?> has posted <?php echo $user_data->thread_count ?> threads and <?php echo $user_data->comment_count ?> comments.
							That's a total of <?php echo $user_data->average_posts ?> posts per day. <?php echo $user_data->username . $user_data->last_login_text ?> 
							
						</div>
						<div id="information-bio" class="standard_profile_info_box">
						<h3>Info</h3>
							<span class='small_profile_caps'>INVITED BY: </span><br/>
							<span class='small_profile_caps'>NAME: </span><br/>
							<span class='small_profile_caps'>LOC: </span><br/>
							<span class='small_profile_caps'>URL 1: </span><a href="#">www.website.com</a><br/>
							<span class='small_profile_caps'>URL 1: </span><a href="#">www.website.com</a><br/>
							<span class='small_profile_caps'>URL 1: </span><a href="#">www.website.com</a><br/>
						</div>
						<div id="information-desc" class="standard_profile_info_box">
							<h3>Description</h3>
							Hey there, this is a description of a bio in something where a user might want to talk about themselves.
						</div>
						<div id="information-widget" class="standard_profile_info_box">
							<h3>Widgets</h3>
								Maybe some sort of over arching ability to plugin whatever you want into here.
						</div>

					</div>
						
					
					<div id="latest-posts">
					<? echo $pagination; ?>
					
					<?php if(!$recent_posts): ?>
						<h1>This user has posted absolutely nothing on __FORUM__</h1>
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
					
				
