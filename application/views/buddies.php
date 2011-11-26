<?php

$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'value' => strlen($username) > 0 ? $username : set_value('username'),
	'maxlength'	=> 18,
	'size'	=> 30
);

$commands = array(
	'buddy' => array(
		'name' => 'command',
		'id' => 'cmd-buddy',
		'value' => '1',
		'checked' => TRUE
	),
	'enemy' => array(
		'name' => 'command',
		'id' => 'cmd-enemy',
		'value' => '2'
	)
);

?>

				<div id="main-title"><h3>Keep your friends close and your enemies closer</h3></div>

				<div id="new-thread">

					<p>We'll highlight your buddies posts and show you when they are online. Remember to use your enemies so that you can ignore all those YH users you just can't tolerate.</p>

					<div class="dotted-bar"></div>

          <form action="/buddies" method="post">

						<div class="biglabel">Add a Buddy / Enemy</div>
						
						<?php if ($error_alert!='') { ?><div class="error_alert"><?php echo $error_alert; ?></div><?php } ?>

						<div id="buddy-input">
							<?php echo form_label('Username:', $username['id']); ?>
							<?php echo form_input($username); ?>

							<?php echo form_submit('submit', 'Add'); ?>
						</div>

						<div id="buddy-radios">
							<?php echo form_label('Pick one:'); ?>

							<div id="buddy-radio-inputs">
								<?php echo form_radio($commands['buddy']); ?>
								<?php echo form_label('Buddy', $commands['buddy']['id']); ?>

								<?php echo form_radio($commands['enemy']); ?>
								<?php echo form_label('Enemy', $commands['enemy']['id']); ?>
							</div>
						</div>

					</form>

					<div class="blueline"></div>

					<div class="biglabel">Buddies</div>

          <div id="buddy-listings">

					<?php if ($buddies != FALSE) {
						foreach($buddies->result() as $buddy) {

              $online_status = (int) $buddy->latest_activity > (time() - 300)
                ? 'online'
                : 'offline';
					?>

						<div class="buddy-listing">
							<div class="username">
							  <a href="/user/<?php echo url_title($buddy->username, 'dash', TRUE) ?>"><?php echo $buddy->username; ?></a>
							</div>
							<div class="online-status <?php echo $online_status; ?>"><?php echo $online_status; ?></div>
							<a class="remove-acq" rel="<?php echo $buddy->id; ?>">remove</a>
							<a class="toggle-acq" rel="<?php echo $buddy->id; ?>">enemize</a>
						</div>

					<?php }} ?>

          </div>

					<div class="blueline"></div>

					<div class="biglabel">Enemies</div>

          <div id="enemy-listings">

					<?php if ($enemies != FALSE) {
						foreach($enemies->result() as $enemy) {

              $online_status = (int) $enemy->latest_activity > (time() - 300)
                ? 'online'
                : 'offline';
					?>

						<div class="enemy-listing">
							<div class="username">
							  <a href="/user/<?php echo url_title($enemy->username, 'dash', TRUE) ?>"><?php echo $enemy->username; ?></a>
							</div>
							<div class="online-status <?php echo $online_status; ?>"><?php echo $online_status; ?></div>
							<a class="remove-acq" rel="<?php echo $enemy->id; ?>">remove</a>
							<a class="toggle-acq" rel="<?php echo $enemy->id; ?>">buddilize</a>
						</div>

					<?php }} ?>

          </div>

					<div class="blueline"></div>

					<script type="text/javascript">
						$('.remove-acq').bind('click', function(){
							master = $(this).parent();
							$.get(
								'/buddies/remove/'+ $(this).attr('rel') +'/<?php echo $this->session->userdata('session_id'); ?>',
								function(data) {
									if (data == 1)
										master.remove();
								}
							);
						});
						
						var b2e = function(){
						  link = $(this);
						  master = $(this).parent();
              link.unbind('click').bind('click', e2b);
						  $.get(
						    '/buddies/move/e/'+ $(this).attr('rel') +'/<?php echo $this->session->userdata('session_id'); ?>',
						    function (data) {
						      if (data == '1')
    						    master.appendTo('#enemy-listings').attr('class', 'enemy-listing');
    						    link.html('buddilize');
    						}
						  );
						};
						
						var e2b = function(){
						  link = $(this);
						  master = $(this).parent();
              link.unbind('click').bind('click', b2e);
						  $.get(
						    '/buddies/move/b/'+ $(this).attr('rel') +'/<?php echo $this->session->userdata('session_id'); ?>',
						    function (data) {
						      if (data == 1)
    						    master.appendTo('#buddy-listings').attr('class', 'buddy-listing');
    						    link.html('enemize');
    						}
						  );
						};
						
						$('.enemy-listing .toggle-acq').bind('click', e2b);
						$('.buddy-listing .toggle-acq').bind('click', b2e);
					</script>

				</div>
