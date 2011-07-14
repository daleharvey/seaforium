<?php

$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'value' => set_value('username'),
	'maxlength'	=> 80,
	'size'	=> 30
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30
);

$password2 = array(
	'name'	=> 'password2',
	'id'	=> 'password2',
	'size'	=> 30
);

$email_address = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value' => set_value('email'),
	'size'	=> 30
);

?>

				<div id="main-title"><h3>ALMOST THERE!!!</h3></div>
				
				<p><strong>We need an email address and a password and you're done!</strong></p>
				
				<div class="dotted-bar"></div>

				<?php if (count($errors)): ?> 
					<div id="messages"><div id="error"><ul><?php foreach($errors as $error) echo '<li>'. $error .'</li>'; ?></ul></div></div>
				<?php endif; ?> 
				
				<div id="preferences">
					
					<form method="post" action="/auth/register">
						
						<div class="inp">
							<?php echo form_label('Email Address', $email_address['id']); ?>
							<?php echo form_input($email_address); ?>
						</div>
						
						<div class="inp">
							<?php echo form_label('Password', $password['id']); ?>
							<?php echo form_password($password); ?>
						</div>
						
						<div class="inp">
							<?php echo form_label('Again please', $password2['id']); ?>
							<?php echo form_password($password2); ?>
						</div>
						
						<?php echo form_hidden('key', $key); ?>
						<?php echo form_submit('submit', 'MESSAGEBOARDS!!!'); ?>
						
					</form>
					
				</div>
