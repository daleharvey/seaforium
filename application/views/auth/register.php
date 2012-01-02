<?php

$form = array(
  'username' => array(
    'name' => 'username',
    'id' => 'username',
    'value' => set_value('username'),
    'maxlength' => 32,
    'size' => 30
  ),
  'email' => array(
    'name' => 'email',
    'id' => 'email',
    'value' => set_value('email'),
    'maxlength' => 255,
    'size' => 30
  ),
  'password' => array(
    'name' => 'password',
    'id' => 'password',
    'size' => 30
  ),
  'confirm-password' => array(
    'name' => 'confirm-password',
    'id' => 'confirm-password',
    'size' => 30
  )
);

?><div id="main-title"><h3>OMG, you can Register?</h3></div>

<div id="new-thread">
  <form method="post" action="/auth/register" id="register-form">
    <div class="inp">
      <?php echo form_error('username'); ?>
      <?php echo form_label('Username:', $form['username']['id']); ?>
      <?php echo form_input($form['username']); ?>
    </div>
    <div class="inp">
      <?php echo form_error('email'); ?>
      <?php echo form_label('Email Address:', $form['email']['id']); ?>
      <?php echo form_input($form['email']); ?>
    </div>
    <div class="inp">
      <?php echo form_error('password'); ?>
      <?php echo form_label('Password:', $form['password']['id']); ?>
      <?php echo form_password($form['password']); ?>
    </div>
    <div class="inp">
      <?php echo form_error('confirm-password'); ?>
      <?php echo form_label('Confirm Password:', $form['confirm-password']['id']); ?>
      <?php echo form_password($form['confirm-password']); ?>
    </div>
    <?php if($this->config->item('use_captcha')) { ?>
    <div class="inp">
      <?php echo form_error('recaptcha_response_field'); ?>
      <label>And yes, a captcha: </label>
      <?php echo $recaptcha; ?>
    </div>
    <?php } ?>
    <input type="submit" value="Register" />
  </form>
</div>