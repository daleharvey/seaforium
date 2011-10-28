<div id="main-title"><h3>OMG, you can Register?</h3></div>

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
   <div id="register-notice">Error: That Username is taken</div>

  <form method="post" action="/newthread" id="register-form">
    <div class="inp">
      <label>Username:</label>
      <input type="text" id="register-username" />
    </div>
    <div class="inp">
      <label>Email Address:</label>
      <input type="email" id="register-email" />
    </div>
    <div class="inp">
       <label>Password: </label>
      <input type="password" id="register-password" />
    </div>
    <div class="inp">
      <label>Confirm Password: </label>
      <input type="password" id="register-password-confirm" />
    </div>
    <input type="submit" value="Register" />
  </form>
</div>
<script type="text/javascript">
<?php
if ($this->config->item('yay_import')) {
	echo "var yay_import = 'true';";
}else{
	echo "var yay_import = 'false';";
}
?>
</script>
<script src="/js/register.js"></script>