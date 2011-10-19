var defaultLoginBox = $('#login-box').html();

$('#login-form').live('submit', function(e) {
  e.preventDefault();

  var data = {
    username: $('#username').val(),
    password: $('#password').val()
  };

  $.ajax({
    url: '/beta/login', type: 'POST', data: data
  }).fail(function(data) {
    $('.error').text(JSON.parse(data.responseText).error);
  }).then(function() {
    document.location.href = '';
  });
});


$('#forgot-password').live('click', function(e){
  e.preventDefault();
  $('#login-box').load('/beta/forgot_password');
});

$('#forgot-request').live('submit', function(e){
  e.preventDefault();

  var data = {
    email: $('#forgot-email').val(),
    key: $('#forgot-key').val()
  };

  $.ajax({
    url: '/beta/forgot_password', type: 'POST', data: data
  }).fail(function(data) {
    $('.error').text(JSON.parse(data.responseText).error);
  }).then(function(data) {
    $('#login-box').html(defaultLoginBox);
    $('.error').text('Password reset email sent');
  });

});

$('#forgot-back').live('click', function(e){
  e.preventDefault();
  $('#login-box').html(defaultLoginBox);
});