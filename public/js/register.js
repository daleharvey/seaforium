$('#register-form').bind('submit', function(e){

  e.preventDefault();

  var data = {
    username: $('#register-username').val(),
    email: $('#register-email').val(),
    password:  $('#register-password').val(),
    password_confirm:  $('#register-password-confirm').val()
  };

  $.ajax({
    url: '/auth/register', type: 'POST', data: data
  }).fail(function(data) {
    $('#register-notice').text("ERROR: " + JSON.parse(data.responseText).error).show();
  }).then(function(data) {
    if (data.method === 'plain') {
      document.location.href = '';
    } else {
      $('#register-notice').html('SUCCESS: You need to activate your account, an activation link has been sent to as a pm on <a href="http://yayhooray.com">yayhooray.com</a>').show();
    }
  });

});
