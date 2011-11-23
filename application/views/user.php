<div id="main-title"><h3><?php echo $user_data->username ?></h3></div>

<div id="user">
<?php

   $flickr_nsid = '';
   $latestposts_css = '';

   if (strlen($user_data->flickr_username) > 0 &&
       $this->config->item('flickr_key')!='') {

     $update = @file_get_contents('http://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key='.$this->config->item('flickr_key').'&username='.urlencode($user_data->flickr_username).'&format=php_serial');

     $update = @unserialize($update);
     if ($update !== false) {
       if (isset($update['user']['nsid'])) {
         $flickr_nsid = $update['user']['nsid'];
         $flickr_nsid = str_replace('@', '%40', $flickr_nsid);
       }
     }
   }

   if (strlen($user_data->flickr_username) > 0 &&
       $this->config->item('flickr_key') != '' &&
       strlen($flickr_nsid) <= 0) {
   ?>
   <div id="photostream">From Flickr:
     <div id="flickr_badge_uber_wrapper">
       Username Not Found :(
     </div>
   </div>

   <?php } else if (strlen($flickr_nsid) > 0) {
     $latestposts_css = '-withflickr';
   ?>

   <div id="photostream">From Flickr:
     <div id="flickr_badge_uber_wrapper">
       <div id="flickr_badge_wrapper">
         <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=10&display=latest&size=s&layout=x&source=user&user=<?php echo $flickr_nsid; ?>"></script>
       </div>
    </div>
  </div>
  <?php } ?>

  <div class="personal_info_box">

  <?php if ($this->uri->segment(2) == $this->session->userdata('username')) { ?>
  <div id="this-is-you" >
    <strong>This is You! <a href='preferences/'>Edit this page</a></strong><br/>
  </div>
  <?php } ?>

  <div id="information" class="standard_profile_info_box">
    <h3><?php echo $user_data->username ?></h3>
    <span class="small_profile_caps">
      <span class="<?php echo strtolower($user_data->friendly_status); ?>"><?php echo $user_data->friendly_status; ?></span>
      <span class="<?php echo strtolower(str_replace(' ', '_', $user_data->online_status)); ?>"><?php echo $user_data->online_status; ?>!</span>
    </span><br/>
    <?php if ($this->sauth->is_logged_in()) { ?>
      &rarr; <a href='/message/send/<?php echo $this->uri->segment(2) ?>'>Send a message</a><br/>
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
							<div id="social_icons">
							<?php if (strlen($user_data->aim) > 0) { ?><a href="aim:goim?screenname=<?php echo $user_data->aim; ?>"><img src="/img/social_icons/aol-icon.png" alt="AOL IM." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->delicious_username) > 0) { ?><a href="http://delicious.com/<?php echo $user_data->delicious_username; ?>"><img src="/img/social_icons/delicious-icon.png" alt="Delicious." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->facebook) > 0) { ?><a href="http://facebook.com/<?php echo $user_data->facebook; ?>"><img src="/img/social_icons/facebook-icon.png" alt="Facebook." class="info_icon" /></a> <?php } ?>
							<?php if ($flickr_nsid!='') { ?><a href="http://www.flickr.com/photos/<?php echo $flickr_nsid; ?>"><img src="/img/social_icons/flickr-icon.png" alt="Flickr." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->gchat) > 0) { ?><a href="gtalk:chat?jid=<?php echo $user_data->gchat; ?>"><img src="/img/social_icons/google-icon.png" alt="Google Chat." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->lastfm) > 0) { ?><a href="http://www.last.fm/user/<?php echo $user_data->lastfm; ?>"><img src="/img/social_icons/lastfm-icon.png" alt="LastFM." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->msn) > 0) { ?><a href="msnim:chat?contact=<?php echo $user_data->msn; ?>"><img src="/img/social_icons/msn-icon.png" alt="MSN Messenger." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->twitter) > 0) { ?><a href="https://twitter.com/#!/<?php echo $user_data->twitter; ?>"><img src="/img/social_icons/twitter-icon.png" alt="Twitter." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->rss_feed_1) > 0) { ?><a href="<?php echo $user_data->rss_feed_1; ?>"><img src="/img/social_icons/rss-icon.png" alt="RSS 1." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->rss_feed_2) > 0) { ?><a href="<?php echo $user_data->rss_feed_2; ?>"><img src="/img/social_icons/rss-icon.png" alt="RSS 2." class="info_icon" /></a> <?php } ?>
							<?php if (strlen($user_data->rss_feed_3) > 0) { ?><a href="<?php echo $user_data->rss_feed_3; ?>"><img src="/img/social_icons/rss-icon.png" alt="RSS 3." class="info_icon" /></a><?php } ?>
							</div>
						</div>
						<div id="information-desc" class="standard_profile_info_box">
							<h3>Description</h3>
							<?php echo $user_data->about_blurb; ?>
						</div>

						<?php if (strlen($user_data->lastfm) > 0) { ?>
						<div id="information-desc" class="standard_profile_info_box">
							<h3>Listening to...</h3>
							<?php
							$listingto = '';
							// sets played date using PHP date
							$date_format = 'M j, y g:ia';

							$update = @file_get_contents("http://ws.audioscrobbler.com/1.0/user/".urlencode($user_data->lastfm)."/recenttracks.txt");
							$update = str_replace( '–', '-', $update ); // replaces en dash with regular dash
							$update = explode("\n", $update);

							$track_num = 1; // starting track number
							foreach($update as $data)
							{
							if(!empty( $data ))
							{
							$info = explode(",", $data, 2); // sperates date by only seperating at fist instance of a comma (since some artist/track have comma in their names

							$played_time = $info[0];
							$info_track = explode(" - ", $info[1]); // seperates artist and title

							$artist = $info_track[0];
							$title = $info_track[1];

							$listingto .= '<div class="lastfm_listing"><span class="lastfm_artist">'.$artist.'</span> - <span class="lastfm_title">'.$title.'</span> <span class="lastfm_date">'.date("$date_format", $played_time).'</span></div>';
							$track_num++; // adds 1 to track number
							}
							}
							echo $listingto;
							echo '<a href="http://last.fm/user/'.$user_data->lastfm.'" title="Last.FM profile.">See '.$user_data->username.' on last.fm</a>';
							?>
						</div>
						<?php } ?>

						<?php if (strlen($user_data->twitter) > 0) { ?>
						<div id="information-desc" class="standard_profile_info_box">
						<script src="http://widgets.twimg.com/j/2/widget.js"></script>
						<script>
						new TWTR.Widget({
						  version: 2,
						  type: 'profile',
						  rpp: 5,
						  interval: 30000,
						  width: 200,
						  height: 300,
						  theme: {
						    shell: {
						      background: '#F9F9f9',
						      color: '#333333'
						    },
						    tweets: {
						      background: '#F9F9f9',
						      color: '#333333',
						      links: '#000000'
						    }
						  },
						  features: {
						    scrollbar: false,
						    loop: false,
						    live: false,
						    behavior: 'all'
						  }
						}).render().setUser('<?php echo $user_data->twitter ?>').start();
						</script>
						</div>
						<?php } ?>

					</div>

					<div id="latest-posts<?php echo $latestposts_css; ?>">
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
