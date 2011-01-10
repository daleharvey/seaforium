<?php

$this->load->model('user_dal');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>New Forum</title>
	<link rel="stylesheet" type="text/css" href="/css/forum.css" />
	<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
	
	<base href="<?php echo site_url(); ?>" />
</head>
<body>
	
	<a name="top"></a>
	
	<div id="wrapper">
		
		<div id="top">
			
			<a href="/">New Forum</a>
			
		</div>
		
		<div id="middle">
			
			<div id="left-column">
			
				<div class="lc-node">
					<?php if (!$this->sauth->is_logged_in()) { ?> 
					<ul>
						<li><a href="/auth/login">Login</a></li>
					</ul>
					<?php } else { ?> 
					Welcome back, <?php echo $this->session->userdata('username'); ?>
					<ul>
						<li><a href="/preferences">Your account</a></li>
						<li><a href="/auth/logout">Logout</a></li>
						<?php //<li><a href="/mail/inbox">8 unread messages</a></li> ?> 
					</ul>
					<?php } ?> 
				</div>
				
				<?php if ($this->sauth->is_logged_in()) { ?> 
				<div class="lc-node">
					<h3>Threads</h3>
				</div>
				
				<div class="lc-node" id="new-thread">
					<a href="/newthread">New Thread</a>
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
