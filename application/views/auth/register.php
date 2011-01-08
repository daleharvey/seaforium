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

if (isset($confirmation))
{
	echo '<div id="confirmation">'. $confirmation .'</div>';
}
elseif (isset($errors) && is_array($errors) && count($errors) > 0)
{
	echo '<div id="error"><ul>';
	
	foreach($errors as $error)
	{
		echo "<li>". $error ."</li>";
	}
	
	echo '</ul></div>';
}

?>

		<div id="secluded-register">
			<?php echo form_open('/auth/register/'. $key); ?> 
				
				<h4>Register</h4>
				
				<div class="secluded-input">
					<?php echo form_label('Username', $username['id']); ?> 
					<?php echo form_input($username); ?> 
				</div>
				
				<div class="secluded-input">
					<?php echo form_label('Password', $password['id']); ?> 
					<?php echo form_password($password); ?> 
				</div>
				
				<div class="secluded-input">
					<?php echo form_label('Again please', $password2['id']); ?> 
					<?php echo form_password($password2); ?> 
				</div>
				
				<div class="secluded-input">
					<?php echo form_label('Email Address', $email_address['id']); ?> 
					<?php echo form_input($email_address); ?> 
				</div>
				
				<div class="secluded-submit">
					<?php echo form_hidden('key', $key); ?>
					<?php echo form_submit('submit', 'Login'); ?> 
				</div>
				
			<?php echo form_close(); ?> 
		</div>
		