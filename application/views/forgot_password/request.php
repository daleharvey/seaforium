<h5>Woops, you forgot your password? We can help.</h5>
<p>Enter in the email address you used to sign up, and I will send you an email with a new password. <a href="#" id="forgot-back">Cancel</a></p>
<p class="error"><?php echo $error; ?></p>

<form method="post" action="/ajax_user/forgot_password" id="forgot-request">
	<label>Email:</label>
	<input type="text" name="email" id="forgot-email" tabindex="1" />
	<input type="hidden" name="key" value="<?php echo $this->session->userdata('session_id'); ?>" id="forgot-key" />
	<button tabindex="2" id="forgot-button">OK!</button>
</form>