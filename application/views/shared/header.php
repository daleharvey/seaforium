<?php

$css = $this->agent->is_mobile() ? "mobile.css" : "forum.css";
$username = $this->session->userdata('username');
$user_id = $this->session->userdata('user_id');

$logged_in = $this->sauth->is_logged_in();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>YayHooray 2.0</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/<?php echo $css; ?>" />
	<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>-->
	<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
	
	<base href="<?php echo site_url(); ?>" />
</head>
<body>
	
	<a name="top"></a>
	
	<div id="wrapper">
		
		<div id="middle">
			
			<div id="left-column">
			
			  <a href="/" id="header">New Yay</a>

				<?php 
					if (!$logged_in) {
						
						$button_texts = array(
							"Get In!",
							"Let's Go!",
							"Do it!",
							"Booya!",
							"Push Me",
							"Zippity!",
							"Engage!",
							"Go For It!"
						);
						
					?> 
				
				<div class="lc-node login" id="login-box">
					<h5>Not a member? Wanna join up? Tell us why!</h5>
					<p><img src="/img/pinkies/07.gif" width="14" height="14" align="absmiddle"/> <a href="/invite" class="white">Click for more info, n00b!</a></p>
					<p class="error"></p>
					
					<form action="/auth/login" method="post" id="login-form">
						<div>
							<label>U:</label><input type="text" name="username" tabindex="1" /><button tabindex="3" type="submit"><?php echo $button_texts[array_rand($button_texts)]; ?></button>
						</div>
						<div>
							<label>P:</label><input type="password" name="password" tabindex="2" /><a href="#" id="forgot-password">Forgot it?</a>
						</div>
					</form>
				</div>
				<script type="text/javascript" src="/js/login.js"></script>
				
				<?php } else { ?> 
				<div class="lc-node welcome">
					<h4>
						Hi, <a href="/user/<?php echo $username; ?>">
							<?php echo $username; ?> 
						</a>
					</h4>

					<a href="/user/<?php echo $username; ?>">
						<img src="/img/emoticons/<?php echo $this->session->userdata('emoticon') ? $user_id : 0; ?>.gif" class="main_avatar" />
					</a>
					
					<ul>
						<li><a href="/preferences">Preferences</a></li>
						<li><a href="/auth/logout" class="logout">Logout</a></li>
					</ul>

				</div>
				<?php } ?> 
				
				<?php if ($logged_in) {
					$unread_messages = $this->message_dal->unread_messages($user_id);
					$available_invites = $this->user_dal->get_invites($user_id);
				?>
				
				<div class="lc-node" id="messaging">
					<ul>
						<li class="messages"><a href="/messages/inbox"><?php if ($unread_messages === 0) { echo "No New Messages"; } else { echo $unread_messages .' Unread Message' .($unread_messages === 1 ? '' : 's'); } ?></a></li>
						<li class="invites"><a href="/invite"><?php if ($available_invites === 0) { echo "No invites"; } else { echo $available_invites .' Remaining Invite' .($available_invites === 1 ? '' : 's'); } ?></a></li>
					</ul>
				</div>
				
				<?php } ?> 
				
				<div class="lc-node" id="threads">
					<h3><a href="/">Threads</a></h3>
					<ul id="thread-categories">
						<li><a href="/f/discussions">Discussions</a></li>
						<li><a href="/f/projects">Projects</a></li>
						<li><a href="/f/advice">Advice</a></li>
						<li><a href="/f/meaningless">Meaningless</a></li>
					</ul>
					<ul id="special-threads">
						<li><a href="/">All Forums</a></li>
						<li><a href="/f/meaningful">All But Meaningless</a></li>
						<?php if ($logged_in) { ?>
						<li><a href="/f/participated">Participated Threads</a></li>
						<li><a href="/f/favorites">Favourite Threads</a></li>
						<?php } ?> 
					</ul>
					<?php if ($logged_in) { ?>
					
					<a id="toggle-html">Turn <?php echo $this->session->userdata('view_html') == '1' ? 'off' : 'on'; ?> html</a>
					
					<?php } ?>
				</div>

				<?php if ($this->sauth->is_logged_in()) { ?> 
				
				<div class="lc-node" id="buddy-list">
					<h3><a href="/buddies">Buddies</a></h3>					
					<?php
						$buddy_info = $this->user_dal->get_active_users($user_id);
					?> 					
					<p>ONLINE BUDDIES (<?php echo $buddy_info['buddies']->num_rows; ?>/<?php echo $buddy_info['buddy_count']; ?>)</p>
					<div>
						<?php
							foreach($buddy_info['buddies']->result() as $user)
							{ ?> 
						<span><?php echo anchor('/user/'.url_title($user->username, 'dash', TRUE), $user->username); ?></span>
						<?php } ?> 
					</div>
				</div>
				<?php } ?> 
				
			</div>
			
			<div id="right-column">
