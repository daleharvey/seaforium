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

					<?php echo form_open('/buddies'); ?>

						<div class="biglabel">Add a Buddy / Enemy</div>
						
						<div class="error_alert"><?php echo $error_alert; ?></div>

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

					<?php echo form_close(); ?>

					<div class="blueline"></div>

					<div class="biglabel">Buddies</div>

					<?php if ($buddies != FALSE) {
						foreach($buddies->result() as $buddy) {

							if ((int) $buddy->latest_activity > (time() - 300))
							{
								$online_status = 'online';
							}
							else
							{
								$online_status = 'offline';
							}
					?>

						<div class="buddy-listing">
							<div class="username"><?php echo anchor('/user/'.url_title($buddy->username, 'dash', TRUE), $buddy->username); ?></div>
							<div class="online-status <?php echo $online_status; ?>"><?php echo $online_status; ?></div>
							<a class="remove-acq" rel="<?php echo $buddy->id; ?>">remove</a>
						</div>

					<?php }} ?>

					<div class="blueline"></div>

					<div class="biglabel">Enemies</div>

					<?php if ($enemies != FALSE) {
						foreach($enemies->result() as $enemy) {

						$online_status = $enemy->latest_activity != '0' && $enemy->latest_activity > (time() - 300) ? 'online' : 'offline';
					?>

						<div class="enemy-listing">
							<div class="username"><?php echo anchor('/user/'.url_title($enemy->username, 'dash', TRUE), $enemy->username); ?></div>
							<div class="online-status <?php echo $online_status; ?>"><?php echo $online_status; ?></div>
							<a class="remove-acq" rel="<?php echo $enemy->id; ?>">remove</a>
						</div>

					<?php }} ?>

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
					</script>

				</div>