<?php

$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'label' => 'Login'
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30
);

?>

<?php echo form_open('/auth/login'); ?>
<table>
	<tr>
		<td><?php echo form_label('Login', $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Password', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
	</tr>
	<tr>
		<td colspan="3">
			<?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?>
			<?php echo anchor('/auth/register/', 'Register'); ?>
		</td>
	</tr>
</table>
<?php echo form_submit('submit', 'Login'); ?>
<?php echo form_close(); ?>