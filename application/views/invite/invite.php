				<div id="main-title"><h3>Sorta Open Beta! WOOO!!!</h3></div>
				
				<div id="invite-page">
					
					<p><strong>You wanna join the ranks? Small bit of bad news, you need to be an existing member of YayHooray.<em>com</em></strong></p>
					
					<div class="dotted-bar"></div>
					
					<?php if (isset($error) && strlen($error) > 0) { ?>
					
					<div class="error">
						<p><?php echo $error; ?></p>
					</div>
					
					<div class="dotted-bar"></div>
					
					<?php } ?>
					
					<form method="post" action="/invite">
						
						<div class="inp">
							<label for="yh_username">We'll need your YH.com username</label>
							<input type="text" name="yh_username" id="yh_username" />
						</div>

						<button type="submit">Send me an invite!</button>
					</form>
					
				</div>