<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Yayhooray 2.0</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/forum.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	
	<base href="<?php echo site_url(); ?>" />
</head>
<body>
	
	<a name="top"></a>
	
	<div id="wrapper">
		
		<div id="middle">
			
			<div id="left-column">
			
			  <a href="/" id="header">New Yay</a>

				<?php
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
					<?php /*<h5>Not a member? Wanna join up? Tell us why!</h5>
					<p><img src="/img/pinkies/07.gif" width="14" height="14" align="absmiddle"/> <a href="/invite" class="white">Click for more info, n00b!</a></p> */ ?> 
					<p class="error"></p>
					
					<form action="/beta/login" method="post" id="login-form">
						<div>
							<label>U:</label><input type="text" name="username" tabindex="1" /><button tabindex="3" type="submit"><?php echo $button_texts[array_rand($button_texts)]; ?></button>
						</div>
						<div>
							<label>P:</label><input type="password" name="password" tabindex="2" /><a href="#" id="forgot-password">Forgot it?</a>
						</div>
					</form>
				</div>
				<script type="text/javascript" src="/js/beta.js"></script>
				
			</div>
