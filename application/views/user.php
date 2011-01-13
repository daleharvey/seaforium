
				<div id="main-title"><?php echo $user_data->username ?></div>

				<div id="user">
					
					<div id="information">
						<?php echo $user_data->username ?> is the <?php echo make_ordinal($user_data->id); ?> member of this place and has been here since <?php echo date('F jS Y', strtotime($user_data->created)); ?>.
						Since then, <?php echo $user_data->username ?> has posted <?php echo $user_data->thread_count ?> threads and <?php echo $user_data->comment_count ?> comments.
						That's a total of <?php echo $user_data->average_posts ?> posts per day. <?php echo $user_data->username . $user_data->last_login_text ?> 
						
					</div>
					
					<div id="latest-posts">
					<?php if(!$recent_posts): ?>
						<h1>This user has posted absolutely nothing on __FORUM__</h1>
					<?php else: 
						foreach($recent_posts as $post):
					?>
						<div class="post-block">POST</div>
						<h3 class="recent-thread-title"><?php echo $post['subject'] ?></h3>
						<div class="clear"></div>
						<div class="recent-post-content">
							<?php echo $post['content']; ?>
						</div>
						<div class="clear"></div>	
						<hr/>
						
					
					<?php
						endforeach; 
					 endif; ?>
					 <div class="clear"></div>
					</div>
					
				</div>
				<div class="clear"></div>
