<?php

$button_texts = array(
	"Get In!",
	"Let's Go!",
	"Do it!",
	"Booya!",
	"Push Me",
	"Zippity!",
	"Engage!",
	"Go For It!"
);

?>
<h5>Not a member? Wanna join up? Tell us why!</h5>
<p>Click for more info, n00b!</p>
<form action="/auth/login" method="post">
	<div>
		<label>U:</label><input type="text" name="username" tabindex="1" /><button tabindex="3"><?php echo $button_texts[array_rand($button_texts)]; ?></button>
	</div>
	<div>
		<label>P:</label><input type="password" name="password" tabindex="2" /><a href="#" id="forgot-password">Forgot it?</a>
	</div>
</form>