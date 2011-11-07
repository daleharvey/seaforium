<?php

$form = array(
  'username' => array(
    'name' => 'username',
    'id' => 'username',
    //'value' => set_value('username'),
    'value' => 'newUsername',
    'maxlength' => 32,
    'size' => 30
  ),
  'email' => array(
    'name' => 'email',
    'id' => 'email',
    //'value' => set_value('email'),
    'value' => 'email@yayhooray.net',
    'maxlength' => 255,
    'size' => 30
  ),
  'password' => array(
    'name' => 'password',
    'id' => 'password',
    'value' => 'p4ssw0rd!',
    'size' => 30
  ),
  'confirm-password' => array(
    'name' => 'confirm-password',
    'id' => 'confirm-password',
    'value' => 'p4ssw0rd!',
    'size' => 30
  )
);

?><div id="main-title"><h3>OMG, you can Register?</h3></div>

<div id="new-thread">
<?php if ($this->config->item('yay_import')) { ?>
   <p>If you register using an existing username on yayhooray.com you will have
     to activate your account to use that username, the link will be sent to
     <a href="http://www.yayhooray.com/messages">yayhooray.com/messages</a>.
   </p>

   <p>If the pm doesn't arrive within 10 minutes, pm
     <a href="http://dh.yayhooray.com">dh</a> for access. Cheers</p>

  <div class="dotted-bar"></div>
<?php } ?>

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