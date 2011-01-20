<?php

$css = $this->agent->is_mobile() ? "mobile.css" : "forum.css";
$username = $this->session->userdata('username');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>New Forum</title>
	<link rel="stylesheet" type="text/css" href="/css/<?php echo $css; ?>" />
	<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
	
	<base href="<?php echo site_url(); ?>" />
</head>
<body>
	
	<a name="top"></a>
	
	<div id="wrapper">
		
		<div id="middle">
			
			<div id="left-column">
			
			  <a href="/" id="header">New Yay</a>

				<div class="lc-node welcome">
					<?php if (!$this->sauth->is_logged_in()) { ?> 
					<ul>
						<li><a href="/auth/login">Login</a></li>
					</ul>
					<?php } else { ?> 
					<h4>
						Hi, <a href="/user/<?php echo $username; ?>">
							<?php echo $username; ?> 
						</a>
					</h4>

					<a href="/user/<?php echo $username; ?>">
						<img src="/img/pinkies/11.gif" class="main_avatar" />
					</a>
					
					<?php
						$unread_messages = $this->message_dal->unread_messages($this->session->userdata('user_id'));
						
						$unread_text = $unread_messages .' unread message' .($unread_messages === 1 ? '' : 's');
					?>
					
					<ul>
						<li><a href="/preferences">Preferences</a></li>
						<li><a href="/auth/logout" class="logout">Logout</a></li>
						<li><a href="/messages/inbox"><?php echo $unread_text; ?></a></li>
					</ul>

					<?php } ?> 
				</div>
				
				<?php if ($this->sauth->is_logged_in()) { ?> 
				<div class="lc-node" id="threads">
					<h3><a href="/">Threads</a></h3>
					<ul id="thread-categories">
						<li><a href="#">Discussions</a></li>
						<li><a href="#">Projects</a></li>
						<li><a href="#">Advice</a></li>
						<li><a href="#">Meaningless</a></li>
					</ul>
					<ul id="special-threads">
						<li><a href="#">All Forums</a></li>
						<li><a href="#">All But Meaningless</a></li>
						<li><a href="#">Participated Threads</a></li>
						<li><a href="#">Favourite Threads</a></li>
					</ul>
				</div>
				
				<?php } ?> 
				
				<div class="lc-node" id="buddy-list">
					<h3 title="within the last 5 minutes">Online Users</h3>
					<div>
						<?php 
							$active_record = $this->user_dal->get_active_users();
							
							foreach($active_record->result() as $user)
							{ ?> 
						<span><?php echo anchor('/user/'.url_title($user->username, 'dash', TRUE), $user->username); ?></span>
						<?php } ?> 
					</div>
				</div>
				
			</div>
			
			<div id="right-column">
