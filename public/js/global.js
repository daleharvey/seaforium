
(function () {
  var title, tpl = $("#title-input").html();

  $("#main-title.changeling").bind("click", function () {
    if ($(this).is(":not(.editing)")) {
      title = $.trim($('h3', this).text());
      $(this).addClass("editing");
      $('h3', this).empty().append(tpl);
      var input = $(this).find("#title-input");
      input.val(title);
      input[0].focus();
      input[0].select();
    }
  });

  $('.youtube_wrapper').live("click", function() {
    var obj = $(this);
    if (!obj.data('active')) {
      obj.data('active', true);
      $(this).html('<iframe width="' + obj.width() + '" height="' +
                   obj.height() + '" src="http://www.youtube.com/embed/' +
                   obj.attr('id') + '?autoplay=1' + obj.data('extra') +
                   '" frameborder="0" allowfullscreen></iframe><br />');
    }
  });

  $("#cancel-title").live("click", function () {
    $('h3', "#main-title").empty().text(title);
    $("#main-title").removeClass("editing");
  });

  $("#save-title").live("click", function () {
    var newTitle = $("#title-input").val();
    var data = "title=" + encodeURIComponent(newTitle);
    data += isThread() ? "&thread_id=" + thread.id() : '';

    $.ajax({
      type: "POST",
      url: "/title/edit",
      data: data,
      success: function(msg){
        $('h3', "#main-title").empty().text(newTitle);
	$("#main-title").removeClass("editing");
      }
    });
  });
})();

$('#search-box').submit(function() {
  window.location.href="find/" + $('#search-phrase').val();
  return false;
});

$('#search-box-user').submit(function() {
  window.location.href="users/0/" + $('#search-phrase-user').val();
  return false;
});

$('#toggle-html').bind('click', function(){
  $.get('/ajax/toggle_html/'+ session_id, function(data) {
    window.location.reload(true);
  });
});

$('.hide-thread').bind('click', function(){

  var button = $(this);
  var toHide = $(this).hasClass('added');
  var url = !toHide ? '/ajax/hide_thread/'+ $(this).attr('rel') +'/'+ session_id :
    '/ajax/unhide_thread/'+ $(this).attr('rel') +'/'+ session_id;

  $.get(url, function(data) {
    if (data == 1) {
      button.toggleClass('added', !toHide);
      button.parent('.five').parent('.thread').slideUp().next().slideUp();
    }
  });
});

$('.favourite').bind('click', function(){

  var button = $(this);
  var id = button.attr('rel');
  if (!$(this).hasClass('added')) {
    $.get('/ajax/favorite_thread/'+ id +'/'+ session_id, function(data) {
      if (data == 1) {
	button.addClass('added');
      }
    });
  } else {
    $.get('/ajax/unfavorite_thread/'+ id +'/'+ session_id, function(data) {
      if (data == 1) {
        button.removeClass('added');
      }
    });
  }
});


function isThread() {
  return (typeof(window.thread) == "undefined")?  false: true;
}


(function() {

  var $input = $('#thread-content-input');
  var $form = $input.parents('form');
  var key = document.title;

  var hasStorage = (function() {
    try {
      return !!localStorage.getItem;
    } catch(e) {
      return false;
    }
  }());

  if ($input.length !== 0 && hasStorage) {

    if (localStorage.getItem(key)) {
      $input.val(localStorage.getItem(key));
    }

    $input.bind('keyup change', function() {
      localStorage.setItem(key, $input.val());
    });
  }


  $form.bind('submit', function(e) {

    e.preventDefault();
    e.stopPropagation();

    if ($input.val().length === 0) {
      return false;
    }

    $form.find('[type=submit]').attr('disabled', 'disabled');

    var data = {
      content: $input.val(),
      ajax: true
    };

    if (document.location.pathname === '/newthread') {
      data.subject = $form.find('#subject').val();
      data['category[]'] = $form.find('input:radio[name=category[]]:checked').val();
    }

    $.ajax({
      type: 'POST',
      url: $form.attr('action'),
      data: data,
      dataType: 'json'
    }).then(function(data) {
      if (hasStorage) {
        localStorage.removeItem(key);
      }
      document.location.href = data.url;
    }).fail(function(data) {
      data = JSON.parse(data.responseText);
      $form.find('[type=submit]').removeAttr('disabled');
      $('#ajax_errs').remove();
      $form.prepend('<div id="ajax_errs">' + data.reason + '</div>');
    });
  });

  var defaultLoginBox = $('#login-box').html();
  $('#login-form').live('submit', function(e) {
    e.preventDefault();

    var data = {
      username: $('#username').val(),
      password: $('#password').val()
    };

    $.ajax({
      url: '/auth/login', type: 'POST', data: data
    }).fail(function(data) {
      $('.error').text(JSON.parse(data.responseText).error);
    }).then(function() {
      window.location.reload();
    });
  });


  $('#forgot-password').live('click', function(e){
    e.preventDefault();
    $('#login-box').load('/auth/forgot_password');
  });

  $('#forgot-request').live('submit', function(e){
    e.preventDefault();

    var data = {
      email: $('#forgot-email').val(),
      key: $('#forgot-key').val()
    };

    $.ajax({
      url: '/auth/forgot_password', type: 'POST', data: data
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

  $('#hide-ads').live('click', function(e){
    e.preventDefault();

    $.ajax({
      url: '/ajax/hide_ads/'+session_id,
      success: function(data){
        if (data == 1)
          window.location.reload(false);
      }
    });
  });

  $('#unhide-ads').live('click', function(e){
    e.preventDefault();

    $.ajax({
      url: '/ajax/show_ads/'+session_id,
      success: function(data){
        if (data == 1)
        if (data == 1)
          window.location.reload(false);
      }
    });
  });

})();