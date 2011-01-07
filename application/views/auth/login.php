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

$yh_username = array(
	'name'	=> 'yhuser',
	'id'	=> 'yhuser',
	'value' => set_value('yhuser'),
	'maxlength'	=> 80,
	'size'	=> 30
);
	
?>

		<?php if (count($errors)): ?> 
			<div id="messages"><div id="error"><ul><?php foreach($errors as $error) echo '<li>'. $error .'</li>'; ?></ul></div></div>
		<?php endif; ?> 

		<div id="secluded-login">
			<?php echo form_open('/auth/login'); ?> 
				
				<h4>Login</h4>
				
				<div class="secluded-input">
					<?php echo form_label('Username', $username['id']); ?> 
					<?php echo form_input($username); ?> 
				</div>
				
				<div class="secluded-input">
					<?php echo form_label('Password', $password['id']); ?> 
					<?php echo form_password($password); ?> 
				</div>
				
				<div class="secluded-submit">
					<?php echo form_submit('submit', 'Login'); ?> 
				</div>
			<?php echo form_close(); ?> 
		</div>
		
		<div id="secluded-invite">
			
			<h4>Get an invite</h4>
			
			<p>Input your YayHooray username and an invite will be sent to you.</p>
			
			<?php echo form_open('/auth/invite'); ?> 
				
				<div class="secluded-input">
					<?php echo form_label('Username', $yh_username['id']); ?> 
					<?php echo form_input($yh_username); ?> 
				</div>
				
				<div class="secluded-submit">
					<?php echo form_submit('submit', 'Send me an invite'); ?> 
				</div>
			<?php echo form_close(); ?> 
			
		</div>