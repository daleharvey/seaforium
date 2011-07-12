var defaultLoginBox = $('#login-box').html();

$('#forgot-password').live('click', function(e){
	e.preventDefault();
	
	$('#login-box').load('/ajax_user/forgot_password');
});

$('#forgot-request').live('submit', function(e){
	e.preventDefault();
	
	$.post(
		'/ajax_user/forgot_password',
		{
			email: $('#forgot-email').val(),
			key: $('#forgot-key').val()
		},
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