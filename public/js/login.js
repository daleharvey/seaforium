var defaultLoginBox = $('#login-box').html();

$('#forgot-password').live('click', function(e){
	e.preventDefault();
	$('#login-box').load('/auth/forgot_password');
});

$('#forgot-request').live('submit', function(e){
	e.preventDefault();
	$.post(
		'/auth/forgot_password',
		{email: $('#forgot-email').val(),
		 key: $('#forgot-key').val()},
		function(data)
		{
			$('#login-box').html(data);
		}
	);
});

$('#forgot-back').live('click', function(e){
	e.preventDefault();
	$('#login-box').html(defaultLoginBox);
});

$('#login-form').live('submit', function(e){
	e.preventDefault();
	$.post(
		'/auth/login',
		{username: $('#login-box input[name=username]').val(),
		 password: $('#login-box input[name=password]').val()},
		function(data)
		{
			if (data == '1') location.reload(true);
			else $('#login-box .error').html("Sorry, wrong password!");
		}
	);
});